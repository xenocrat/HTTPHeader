<?php
    namespace xenocrat;

    class HTTPHeader {
        const HTTPHEADER_VERSION_MAJOR = 4;
        const HTTPHEADER_VERSION_MINOR = 0;

        private static function header_extract($name, $string): string|false {
            if (!is_string($string))
                throw new \Exception("HTTP header must be a string.");

            $return = false;

            if (strpos($string, "\r\n\r\n") !== false) {
                $fields = explode("\r\n", strstr($string, "\r\n\r\n", true));

                foreach ($fields as $field) {
                    if (
                        preg_match(
                            "/^($name:)(.+)$/i",
                            $field,
                            $match
                        )
                    ) {
                        $return = $match[2];
                        self::trim_whitespace($return);
                    }
                }
            } elseif (
                preg_match(
                    "/^($name:)?(.+?)(\r\n)?$/i",
                    $string,
                    $match
                )
            ) {
                $return = $match[2];
                self::trim_whitespace($return);
            }

            return $return;
        }

        private static function header_request($name): string|false {
            return isset($_SERVER[$name]) ? $_SERVER[$name] : false ;
        }

        private static function trim_whitespace(&$mixed): void {
            if (is_array($mixed)) {
                foreach ($mixed as &$item)
                    self::trim_whitespace($item);
            }

            if (is_string($mixed))
                $mixed = trim($mixed, " ");
        }

        private static function filter_no_empty(&$array): void {
            foreach ($array as &$value) {
                if (is_array($value))
                    self::filter_no_empty($value);
            }

            $array = array_filter(
                $array,
                function($string) {
                    return ($string !== "");
                }
            );
        }

        private static function explode_quoted($delimiter, $string): ?array {
            if (
                preg_match_all(
                    "/(?<!\\\\)\"/",
                    $string,
                ) % 2 != 0
            )
                return null;

            $array = explode($delimiter, $string);
            $chunk = "";
            $fixed = array();

            foreach ($array as $value) {
                $chunk.= $value;

                if (
                    preg_match_all(
                        "/(?<!\\\\)\"/",
                        $chunk
                    ) % 2 == 0
                ) {
                    $fixed[] = $chunk;
                    $chunk = "";
                }
            }

            return $fixed;
        }

        private static function q_sort($a, $b): int {
            $a_q = preg_match(
                "/;q=([0-9\.]+)$/",
                $a,
                $a_match
            ) ? floatval($a_match[1]) : 1.0 ;

            $b_q = preg_match(
                "/;q=([0-9\.]+)$/",
                $b,
                $b_match
            ) ? floatval($b_match[1]) : 1.0 ;

            if ($a_q == $b_q)
                return 0;

            return ($a_q > $b_q) ? -1 : 1 ;
        }

        public static function Accept($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCEPT");
            else
                $value = self::header_extract("Accept", $string);

            if ($value === false)
                return false;

            $types = explode(",", $value);
            self::trim_whitespace($types);
            self::filter_no_empty($types);
            usort($types, "self::q_sort");
            return $types;
        }

        public static function Accept_CH($string): array|false {
            $value = self::header_extract("Accept-CH", $string);

            if ($value === false)
                return false;

            $hints = explode(",", $value);
            self::trim_whitespace($hints);
            self::filter_no_empty($hints);
            return $hints;
        }

        public static function Accept_Charset($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCEPT_CHARSET");
            else
                $value = self::header_extract("Accept-Charset", $string);

            if ($value === false)
                return false;

            $charsets = explode(",", $value);
            self::trim_whitespace($charsets);
            self::filter_no_empty($charsets);
            usort($charsets, "self::q_sort");
            return $charsets;
        }

        public static function Accept_Encoding($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCEPT_ENCODING");
            else
                $value = self::header_extract("Accept-Encoding", $string);

            if ($value === false)
                return false;

            $encodings = explode(",", $value);
            self::trim_whitespace($encodings);
            self::filter_no_empty($encodings);
            usort($encodings, "self::q_sort");
            return $encodings;
        }

        public static function Accept_Language($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCEPT_LANGUAGE");
            else
                $value = self::header_extract("Accept-Language", $string);

            if ($value === false)
                return false;

            $languages = explode(",", $value);
            self::trim_whitespace($languages);
            self::filter_no_empty($languages);
            usort($languages, "self::q_sort");
            return $languages;
        }

        public static function Accept_Patch($string): array|false {
            $value = self::header_extract("Accept-Patch", $string);

            if ($value === false)
                return false;

            $types = explode(",", $value);
            self::trim_whitespace($types);
            self::filter_no_empty($types);
            return $types;
        }

        public static function Accept_Post($string): array|false {
            $value = self::header_extract("Accept-Post", $string);

            if ($value === false)
                return false;

            $types = explode(",", $value);
            self::trim_whitespace($types);
            self::filter_no_empty($types);
            return $types;
        }

        public static function Accept_Ranges($string): string|false {
            $value = self::header_extract("Accept-Ranges", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Access_Control_Allow_Credentials($string): ?bool {
            $value = self::header_extract("Access-Control-Allow-Credentials", $string);

            if ($value === false)
                return false;

            return ($value === "true") ? true : null ;
        }

        public static function Access_Control_Allow_Headers($string): array|false {
            $value = self::header_extract("Access-Control-Allow-Headers", $string);

            if ($value === false)
                return false;

            $headers = explode(",", $value);
            self::trim_whitespace($headers);
            self::filter_no_empty($headers);
            return $headers;
        }

        public static function Access_Control_Allow_Methods($string): array|null|false {
            $value = self::header_extract("Access-Control-Allow-Methods", $string);

            if ($value === false)
                return false;

            $methods = explode(",", $value);
            self::trim_whitespace($methods);
            self::filter_no_empty($methods);

            foreach ($methods as $method) {
                switch ($method) {
                    case "GET":
                    case "HEAD":
                    case "POST":
                    case "PUT":
                    case "DELETE":
                    case "CONNECT":
                    case "OPTIONS":
                    case "TRACE":
                    case "PATCH":
                        break;
                    default:
                        return null;
                }
            }

            return $methods;
        }

        public static function Access_Control_Allow_Origin($string): string|array|null|false {
            $value = self::header_extract("Access-Control-Allow-Origin", $string);

            if ($value === false)
                return false;

            if ($value === "*")
                return $value;

            if ($value === "null")
                return null;

            $origin = parse_url($value);

            if ($origin !== false)
                return $origin;

            return null;
        }

        public static function Access_Control_Expose_Headers($string): array|false {
            $value = self::header_extract("Access-Control-Expose-Headers", $string);

            if ($value === false)
                return false;

            $headers = explode(",", $value);
            self::trim_whitespace($headers);
            self::filter_no_empty($headers);
            return $headers;
        }

        public static function Access_Control_Max_Age($string): int|null|false {
            $value = self::header_extract("Access-Control-Max-Age", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Access_Control_Request_Headers($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCESS_CONTROL_REQUEST_HEADERS");
            else
                $value = self::header_extract("Access-Control-Request-Headers", $string);

            if ($value === false)
                return false;

            $headers = explode(",", $value);
            self::trim_whitespace($headers);
            self::filter_no_empty($headers);
            return $headers;
        }

        public static function Access_Control_Request_Method($string = null): string|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCESS_CONTROL_REQUEST_METHOD");
            else
                $value = self::header_extract("Access-Control-Request-Method", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "GET":
                case "HEAD":
                case "POST":
                case "PUT":
                case "DELETE":
                case "CONNECT":
                case "OPTIONS":
                case "TRACE":
                case "PATCH":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Age($string): int|null|false {
            $value = self::header_extract("Age", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Allow($string): array|null|false {
            $value = self::header_extract("Allow", $string);

            if ($value === false)
                return false;

            $methods = explode(",", $value);
            self::trim_whitespace($methods);
            self::filter_no_empty($methods);

            foreach ($methods as $method) {
                switch ($method) {
                    case "GET":
                    case "HEAD":
                    case "POST":
                    case "PUT":
                    case "DELETE":
                    case "CONNECT":
                    case "OPTIONS":
                    case "TRACE":
                    case "PATCH":
                        break;
                    default:
                        return null;
                }
            }

            return $methods;
        }

        public static function Alt_Svc($string): array|false {
            $value = self::header_extract("Alt-Svc", $string);

            if ($value === false)
                return false;

            $services = explode(",", $value);

            foreach ($services as &$service)
                $service = explode(";", $service);

            self::trim_whitespace($services);
            self::filter_no_empty($services);
            return $services;
        }

        public static function Authorization($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_AUTHORIZATION");
            else
                $value = self::header_extract("Authorization", $string);

            if ($value === false)
                return false;

            $array = preg_split(
                "/ +/",
                $value,
                2,
                PREG_SPLIT_NO_EMPTY
            );

            if (count($array) < 2)
                return null;

            self::trim_whitespace($array);
            return $array;
        }

        public static function Cache_Control($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_CACHE_CONTROL");
            else
                $value = self::header_extract("Cache-Control", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);
            return $directives;
        }

        public static function Clear_Site_Data($string): array|false {
            $value = self::header_extract("Clear-Site-Data", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            foreach ($directives as &$directive)
                $directive = stripslashes(trim($directive, "\""));

            return $directives;
        }

        public static function Connection($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_CONNECTION");
            else
                $value = self::header_extract("Connection", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);
            return $directives;
        }

        public static function Content_Disposition($string): array|null|false {
            $value = self::header_extract("Content-Disposition", $string);

            if ($value === false)
                return false;

            $params = self::explode_quoted(";", $value);

            if ($params === null)
                return null;

            self::trim_whitespace($params);
            self::filter_no_empty($params);

            if (empty($params))
                return null;

            $return = array("disposition" => array_shift($params));

            foreach ($params as $param) {
                if (!preg_match("/^(name|filename\*?)=\"(.+)\"$/", $param, $match))
                    return null;

                $return[$match[1]] = stripslashes($match[2]);
            }

            return $return;
        }

        public static function Content_Encoding($string): array|null|false {
            $value = self::header_extract("Content-Encoding", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            foreach ($directives as $directive) {
                switch ($directive) {
                    case "gzip":
                    case "compress":
                    case "deflate":
                    case "br":
                        break;
                    default:
                        return null;
                }
            }

            return $directives;
        }

        public static function Content_Language($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_CONTENT_LANGUAGE");
            else
                $value = self::header_extract("Content-Language", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);
            return $directives;
        }

        public static function Content_Length($string = null): int|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_CONTENT_LENGTH");
            else
                $value = self::header_extract("Content-Length", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Content_Location($string): string|false {
            $value = self::header_extract("Content-Location", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Content_Range($string): array|null|false {
            $value = self::header_extract("Content-Range", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^([a-zA-Z0-9]+) ([0-9]+\-[0-9]+|\*)\/([0-9]+|\*)$/",
                    $value,
                    $match
                )
            )
                return null;

            $return = array(
                "unit" => $match[1],
                "range" => $match[2],
                "size" => $match[3]
            );

            self::trim_whitespace($return);
            return $return;
        }

        public static function Content_Security_Policy($string): array|false {
            $value = self::header_extract("Content-Security-Policy", $string);

            if ($value === false)
                return false;

            $policy = explode(";", $value);
            self::trim_whitespace($policy);
            self::filter_no_empty($policy);

            foreach ($policy as &$directive) {
                $params = explode(" ", $directive);
                $directive = array(array_shift($params), $params);
            }

            self::trim_whitespace($policy);
            self::filter_no_empty($policy);
            return $policy;
        }

        public static function Content_Security_Policy_Report_Only($string): array|false {
            $value = self::header_extract("Content-Security-Policy-Report-Only", $string);

            if ($value === false)
                return false;

            $policy = explode(";", $value);
            self::trim_whitespace($policy);
            self::filter_no_empty($policy);

            foreach ($policy as &$directive) {
                $params = explode(" ", $directive);
                $directive = array(array_shift($params), $params);
            }

            self::trim_whitespace($policy);
            self::filter_no_empty($policy);
            return $policy;
        }

        public static function Content_Type($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_CONTENT_TYPE");
            else
                $value = self::header_extract("Content-Type", $string);

            if ($value === false)
                return false;

            $params = explode(";", $value);
            self::trim_whitespace($params);
            self::filter_no_empty($params);

            if (empty($params))
                return null;

            $return = array("type" => array_shift($params));

            foreach ($params as $param) {
                if (
                    !preg_match(
                        "/^(charset|boundary)=(.+)$/",
                        $param,
                        $match
                    )
                )
                    return null;

                $return[$match[1]] = $match[2];
            }

            return $return;
        }

        public static function Cookie($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_COOKIE");
            else
                $value = self::header_extract("Cookie", $string);

            if ($value === false)
                return false;

            $pairs = preg_split(
                "/; /",
                $value,
                0,
                PREG_SPLIT_NO_EMPTY
            );

            self::trim_whitespace($pairs);
            self::filter_no_empty($pairs);

            if (empty($pairs))
                return null;

            $cookies = array();

            foreach ($pairs as $pair) {
                if (
                    !preg_match(
                        "/^([^()<>@,;:\\\\ \"\/\[\]\?={}\t]+)=\"?([^\",;\\\\]+)\"?$/",
                        $pair,
                        $match
                    )
                )
                    return null;

                $name = str_replace(".", "_", $match[1]);
                $cookies[$name] = $match[2];
            }

            return $cookies;
        }

        public static function Cross_Origin_Embedder_Policy($string): string|null|false {
            $value = self::header_extract("Cross-Origin-Embedder-Policy", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "unsafe-none":
                case "require-corp":
                case "credentialless":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Cross_Origin_Opener_Policy($string): string|null|false {
            $value = self::header_extract("Cross-Origin-Opener-Policy", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "unsafe-none":
                case "same-origin-allow-popups":
                case "same-origin":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Cross_Origin_Resource_Policy($string): string|null|false {
            $value = self::header_extract("Cross-Origin-Resource-Policy", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "same-site":
                case "same-origin":
                case "cross-origin":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Date($string = null): \DateTimeImmutable|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_DATE");
            else
                $value = self::header_extract("Date", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^[A-Z][a-z]{2}, [0-9]{2} [A-Z][a-z]{2} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/",
                    $value
                )
            )
                return null;

            $date = date_create_immutable($value);

            if ($date !== false)
                return $date;

            return null;
        }

        public static function Device_Memory($string = null): float|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_DEVICE_MEMORY");
            else
                $value = self::header_extract("Device-Memory", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9\.]+$/",
                $value
            ) ? floatval($value) : null ;
        }

        public static function Downlink($string = null): float|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_DOWNLINK");
            else
                $value = self::header_extract("Downlink", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9\.]+$/",
                $value
            ) ? floatval($value) : null ;
        }

        public static function DNT($string = null): int|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_DNT");
            else
                $value = self::header_extract("DNT", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "0":
                    return 0;
                case "1":
                    return 1;
                default:
                    return null;
            }
        }

        public static function ETag($string): string|null|false {
            $value = self::header_extract("ETag", $string);

            if ($value === false)
                return false;

            if (
                preg_match(
                    "/^(W\/)?\".+\"$/",
                    $value
                )
            )
                return stripslashes($value);

            return null;
        }

        public static function Expect($string = null): int|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_EXPECT");
            else
                $value = self::header_extract("Expect", $string);

            if ($value === false)
                return false;

            return ($value === "100-continue") ? 100 : null ;
        }

        public static function Expires($string): \DateTimeImmutable|null|false {
            $value = self::header_extract("Expires", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^[A-Z][a-z]{2}, [0-9]{2} [A-Z][a-z]{2} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/",
                    $value
                )
            )
                return null;

            $date = date_create_immutable($value);

            if ($date !== false)
                return $date;

            return null;
        }

        public static function Forwarded($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_FORWARDED");
            else
                $value = self::header_extract("Forwarded", $string);

            if ($value === false)
                return false;

            $fields = explode(",", $value);
            self::trim_whitespace($fields);
            self::filter_no_empty($fields);
            $return = array();

            foreach ($fields as $field) {
                $parts = explode(";", $field);
                self::trim_whitespace($parts);
                self::filter_no_empty($parts);
                $directive = array();

                foreach ($parts as $part) {
                    if (
                        !preg_match(
                            "/^(by|for|host|proto)=(.+)$/i",
                            $part,
                            $match
                        )
                    )
                        return null;

                    $directive[strtolower($match[1])] = $match[2];
                }

                $return[] = $directive;
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function From($string = null): string|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_FROM");
            else
                $value = self::header_extract("From", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^[^@ ]+@[^@ ]+$/",
                    $value
                )
            )
                return null;

            return $value;
        }

        public static function Host($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_HOST");
            else
                $value = self::header_extract("Host", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^(.+?)(:([0-9]+))?$/",
                    $value,
                    $match
                )
            )
                return null;

            $return = array("host" => $match[1]);

            if (isset($match[3]))
                $return["port"] = $match[3];

            return $return;
        }

        public static function If_Match($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_MATCH");
            else
                $value = self::header_extract("If-Match", $string);

            if ($value === false)
                return false;

            $etags = self::explode_quoted(",", $value);

            if ($etags === null)
                return null;

            self::trim_whitespace($etags);
            self::filter_no_empty($etags);

            foreach ($etags as &$etag) {
                if (
                    !preg_match(
                        "/^((W\/)?\".+\"|\*)$/",
                        $etag
                    )
                )
                    return null;

                $etag = stripslashes($etag);
            }

            return $etags;
        }

        public static function If_Modified_Since($string = null): \DateTimeImmutable|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_MODIFIED_SINCE");
            else
                $value = self::header_extract("If-Modified-Since", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^[A-Z][a-z]{2}, [0-9]{2} [A-Z][a-z]{2} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/",
                    $value
                )
            )
                return null;

            $date = date_create_immutable($value);

            if ($date !== false)
                return $date;

            return null;
        }

        public static function If_None_Match($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_NONE_MATCH");
            else
                $value = self::header_extract("If-None-Match", $string);

            if ($value === false)
                return false;

            $etags = self::explode_quoted(",", $value);

            if ($etags === null)
                return null;

            self::trim_whitespace($etags);

            foreach ($etags as &$etag) {
                if (
                    !preg_match(
                        "/^((W\/)?\".+\"|\*)$/",
                        $etag
                    )
                )
                    return null;

                $etag = stripslashes($etag);
            }

            return $etags;
        }

        public static function If_Range($string = null): \DateTimeImmutable|string|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_RANGE");
            else
                $value = self::header_extract("If-Range", $string);

            if ($value === false)
                return false;

            if (
                preg_match(
                    "/^(W\/)?\".+\"$/",
                    $value
                )
            )
                return stripslashes($value);

            if (
                !preg_match(
                    "/^[A-Z][a-z]{2}, [0-9]{2} [A-Z][a-z]{2} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/",
                    $value
                )
            )
                return null;

            $date = date_create_immutable($value);

            if ($date !== false)
                return $date;

            return null;
        }

        public static function If_Unmodified_Since($string = null): \DateTimeImmutable|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_UNMODIFIED_SINCE");
            else
                $value = self::header_extract("If-Unmodified-Since", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^[A-Z][a-z]{2}, [0-9]{2} [A-Z][a-z]{2} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/",
                    $value
                )
            )
                return null;

            $date = date_create_immutable($value);

            if ($date !== false)
                return $date;

            return null;
        }

        public static function Keep_Alive($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_KEEP_ALIVE");
            else
                $value = self::header_extract("Keep-Alive", $string);

            if ($value === false)
                return false;

            $params = explode(",", $value);
            self::trim_whitespace($params);
            $return = array();

            foreach ($params as $param) {
                if (
                    !preg_match(
                        "/^(timeout|max)=(.+)$/",
                        $param,
                        $match
                    )
                )
                    return null;

                $return[$match[1]] = $match[2];
            }

            return $return;
        }

        public static function Last_Modified($string): \DateTimeImmutable|null|false {
            $value = self::header_extract("Last-Modified", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^[A-Z][a-z]{2}, [0-9]{2} [A-Z][a-z]{2} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/",
                    $value
                )
            )
                return null;

            $date = date_create_immutable($value);

            if ($date !== false)
                return $date;

            return null;
        }

        public static function Link($string): array|null|false {
            $value = self::header_extract("Link", $string);

            if ($value === false)
                return false;

            $fields = self::explode_quoted(",", $value);

            if ($fields === null)
                return null;

            self::trim_whitespace($fields);
            self::filter_no_empty($fields);
            $return = array();

            foreach ($fields as $field) {
                $params = self::explode_quoted(";", $field);

                if ($params === null)
                    return null;

                self::trim_whitespace($params);
                self::filter_no_empty($params);

                if (empty($params))
                    return null;

                $uri = array_shift($params);

                if (
                    !preg_match(
                        "/^<(.+)>$/",
                        $uri,
                        $extracted
                    )
                )
                    return null;

                $directive = array($extracted[1], array());

                foreach ($params as $param) {
                    if (
                        !preg_match(
                            "/^([^=]+)=(\"?.+?\"?)$/",
                            $param,
                            $match
                        )
                    )
                        return null;

                    $pn = str_replace(".", "_", $match[1]);
                    $pv = trim($match[2], " ");

                    if (strpos($pv, "\"") === 0)
                        $pv = stripslashes(trim($pv, "\""));

                    $directive[1][$pn] = $pv;
                }

                $return[] = $directive;
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function Location($string): string|false {
            $value = self::header_extract("Location", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Max_Forwards($string = null): int|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_MAX_FORWARDS");
            else
                $value = self::header_extract("Max-Forwards", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Origin($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_ORIGIN");
            else
                $value = self::header_extract("Origin", $string);

            if ($value === false)
                return false;

            $origin = parse_url($value);

            if ($origin !== false)
                return $origin;

            return null;
        }

        public static function Permissions_Policy($string): array|false {
            $value = self::header_extract("Permissions-Policy", $string);

            if ($value === false)
                return false;

            $policy = explode(",", $value);
            self::trim_whitespace($policy);
            self::filter_no_empty($policy);

            foreach ($policy as &$directive) {
                if (
                    !preg_match(
                        "/^([a-zA-Z0-9\-]+)=(\*|\(([^)]*)\))$/",
                        $directive,
                        $match
                    )
                )
                    return null;

                $params = isset($match[3]) ?
                    explode(" ", $match[3]) :
                    array($match[2]) ;

                $directive = array($match[1], $params);
            }

            self::trim_whitespace($policy);
            self::filter_no_empty($policy);
            return $policy;
        }

        public static function Pragma($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_PRAGMA");
            else
                $value = self::header_extract("Pragma", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);
            return $directives;
        }

        public static function Proxy_Authenticate($string): array|null|false {
            $value = self::header_extract("Proxy-Authenticate", $string);

            if ($value === false)
                return false;

            $array = preg_split(
                "/(^|, *)([a-zA-Z0-9_\-]+)( +|$)/",
                rtrim($value, ", "),
                0,
                PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
            );

            $array = array_filter(
                $array,
                function($string) {
                    return preg_match(
                        "/^( +|, *)$/",
                        $string
                    ) ? false : true ;
                }
            );

            $challenges = array();
            $count = 0;

            foreach ($array as $chunk) {
                if (
                    preg_match(
                        "/^[a-zA-Z0-9_\-]+$/",
                        $chunk,
                        $match,
                    )
                ) {
                    if (isset($challenges[$count]))
                        $count++;

                    $challenges[$count] = array($chunk);
                    continue;
                }

                if (!isset($challenges[$count]))
                    return null;

                $challenges[$count][] = $chunk;
            }

            self::trim_whitespace($challenges);
            return $challenges;
        }

        public static function Proxy_Authorization($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_PROXY_AUTHORIZATION");
            else
                $value = self::header_extract("Proxy-Authorization", $string);

            if ($value === false)
                return false;

            $array = preg_split(
                "/ +/",
                $value,
                2,
                PREG_SPLIT_NO_EMPTY
            );

            if (count($array) < 2)
                return null;

            self::trim_whitespace($array);
            return $array;
        }

        public static function Range($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_RANGE");
            else
                $value = self::header_extract("Range", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^([a-zA-Z0-9]+)=([0-9 ,\-]+)$/",
                    $value,
                    $match
                )
            )
                return null;

            $return = array(
                "unit" => $match[1],
                "ranges" => explode(",", $match[2])
            );

            self::trim_whitespace($return);
            return $return;
        }

        public static function Referer($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_REFERER");
            else
                $value = self::header_extract("Referer", $string);

            if ($value === false)
                return false;

            $referer = parse_url($value);

            if ($referer !== false)
                return $referer;

            return null;
        }

        public static function Referrer_Policy($string): string|null|false {
            $value = self::header_extract("Referrer-Policy", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "no-referrer":
                case "no-referrer-when-downgrade":
                case "origin":
                case "origin-when-cross-origin":
                case "same-origin":
                case "strict-origin":
                case "strict-origin-when-cross-origin":
                case "unsafe-url":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Retry_After($string): \DateTimeImmutable|int|null|false {
            $value = self::header_extract("Retry-After", $string);

            if ($value === false)
                return false;

            if (
                preg_match(
                    "/^[0-9]+$/",
                    $value
                )
            )
                return intval($value);

            if (
                !preg_match(
                    "/^[A-Z][a-z]{2}, [0-9]{2} [A-Z][a-z]{2} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/",
                    $value
                )
            )
                return null;

            $date = date_create_immutable($value);

            if ($date !== false)
                return $date;

            return null;
        }

        public static function RTT($string = null): int|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_RTT");
            else
                $value = self::header_extract("RTT", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Save_Data($string = null): int|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_SAVE_DATA");
            else
                $value = self::header_extract("Save-Data", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "0":
                case "Off":
                case "off":
                    return 0;
                case "1":
                case "On":
                case "on":
                    return 1;
                default:
                    return null;
            }
        }

        public static function Sec_Fetch_Dest($string = null): string|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_SEC_FETCH_DEST");
            else
                $value = self::header_extract("Sec-Fetch-Dest", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "audio":
                case "audioworklet":
                case "document":
                case "embed":
                case "empty":
                case "font":
                case "frame":
                case "iframe":
                case "image":
                case "manifest":
                case "object":
                case "paintworklet":
                case "report":
                case "script":
                case "serviceworker":
                case "sharedworker":
                case "style":
                case "track":
                case "video":
                case "worker":
                case "xslt":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Sec_Fetch_Mode($string = null): string|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_SEC_FETCH_MODE");
            else
                $value = self::header_extract("Sec-Fetch-Mode", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "cors":
                case "navigate":
                case "no-cors":
                case "same-origin":
                case "websocket":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Sec_Fetch_Site($string = null): string|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_SEC_FETCH_SITE");
            else
                $value = self::header_extract("Sec-Fetch-Site", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "cross-site":
                case "same-origin":
                case "same-site":
                case "none":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Sec_Fetch_User($string = null): ?bool {
            if (!isset($string))
                $value = self::header_request("HTTP_SEC_FETCH_USER");
            else
                $value = self::header_extract("Sec-Fetch-User", $string);

            if ($value === false)
                return false;

            return ($value === "?1") ? true : null ;
        }

        public static function Sec_GPC($string = null): ?bool {
            if (!isset($string))
                $value = self::header_request("HTTP_SEC_GPC");
            else
                $value = self::header_extract("Sec-GPC", $string);

            if ($value === false)
                return false;

            return ($value === "1") ? true : null ;
        }

        public static function Sec_Purpose($string = null): string|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_SEC_PURPOSE");
            else
                $value = self::header_extract("Sec-Purpose", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "prefetch":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Server($string): string|false {
            $value = self::header_extract("Server", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Server_Timing($string): array|null|false {
            $value = self::header_extract("Server-Timing", $string);

            if ($value === false)
                return false;

            $pieces = self::explode_quoted(",", $value);

            if ($pieces === null)
                return null;

            self::trim_whitespace($pieces);
            self::filter_no_empty($pieces);

            $return = array();

            foreach ($pieces as $piece) {
                $params = self::explode_quoted(";", $piece);

                if ($params === null)
                    return null;

                self::trim_whitespace($params);
                self::filter_no_empty($params);

                $metric = array("name" => array_shift($params));

                foreach ($params as $param) {
                    if (
                        !preg_match(
                            "/^(desc|dur)=(\"?.+?\"?)$/",
                            $param,
                            $match
                        )
                    )
                        return null;

                    $pn = str_replace(".", "_", $match[1]);
                    $pv = trim($match[2], " ");

                    if (strpos($pv, "\"") === 0)
                        $pv = stripslashes(trim($pv, "\""));

                    $metric[$pn] = $pv;
                }

                $return[] = $metric;
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function TE($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_TE");
            else
                $value = self::header_extract("TE", $string);

            if ($value === false)
                return false;

            $encodings = explode(",", $value);
            self::trim_whitespace($encodings);
            self::filter_no_empty($encodings);
            usort($encodings, "self::q_sort");
            return $encodings;
        }

        public static function Upgrade($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_UPGRADE");
            else
                $value = self::header_extract("Upgrade", $string);

            if ($value === false)
                return false;

            $protocols = explode(",", $value);
            self::trim_whitespace($protocols);
            self::filter_no_empty($protocols);
            return $protocols;
        }

        public static function Upgrade_Insecure_Requests($string = null): int|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_UPGRADE_INSECURE_REQUESTS");
            else
                $value = self::header_extract("Upgrade-Insecure-Requests", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "0":
                    return 0;
                case "1":
                    return 1;
                default:
                    return null;
            }
        }

        public static function User_Agent($string = null): array|null|false {
            if (!isset($string))
                $value = self::header_request("HTTP_USER_AGENT");
            else
                $value = self::header_extract("User-Agent", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^([^\/]+)\/([0-9\.]+) (.+)$/",
                    $value,
                    $match
                )
            )
                return null;

            $return = array(
                "product" => $match[1],
                "version" => $match[2],
                "comment" => $match[3]
            );

            return $return;
        }

        public static function Vary($string): array|false {
            $value = self::header_extract("Vary", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);
            return $directives;
        }

        public static function Via($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_VIA");
            else
                $value = self::header_extract("Via", $string);

            if ($value === false)
                return false;

            $proxies = explode(",", $value);
            self::trim_whitespace($proxies);
            self::filter_no_empty($proxies);
            return $proxies;
        }

        public static function Want_Digest($string = null): array|false {
            if (!isset($string))
                $value = self::header_request("HTTP_WANT_DIGEST");
            else
                $value = self::header_extract("Want-Digest", $string);

            if ($value === false)
                return false;

            $algorithms = explode(",", $value);
            self::trim_whitespace($algorithms);
            self::filter_no_empty($algorithms);
            usort($algorithms, "self::q_sort");
            return $algorithms;
        }

        public static function WWW_Authenticate($string): array|null|false {
            $value = self::header_extract("WWW-Authenticate", $string);

            if ($value === false)
                return false;

            $array = preg_split(
                "/(^|, *)([a-zA-Z0-9_\-]+)( +|$)/",
                rtrim($value, ", "),
                0,
                PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
            );

            $array = array_filter(
                $array,
                function($string) {
                    return preg_match(
                        "/^( +|, *)$/",
                        $string
                    ) ? false : true ;
                }
            );

            $challenges = array();
            $count = 0;

            foreach ($array as $chunk) {
                if (
                    preg_match(
                        "/^[a-zA-Z0-9_\-]+$/",
                        $chunk,
                        $match,
                    )
                ) {
                    if (isset($challenges[$count]))
                        $count++;

                    $challenges[$count] = array($chunk);
                    continue;
                }

                if (!isset($challenges[$count]))
                    return null;

                $challenges[$count][] = $chunk;
            }

            self::trim_whitespace($challenges);
            return $challenges;
        }
    }
