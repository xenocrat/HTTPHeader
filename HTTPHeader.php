<?php
    namespace xenocrat;

    class HTTPHeader {
        const VERSION_MAJOR = 4;
        const VERSION_MINOR = 9;
        const VERSION_PATCH = 0;

        protected static function header_from_server(
            $name
        ): string|false {
            if (!isset($_SERVER[$name]))
                return false;

            $value = $_SERVER[$name];
            self::trim_whitespace($value);
            return ($value != "") ? $value : false ;
        }

        protected static function header_from_string(
            $name,
            $string
        ): string|false {
            if (!is_string($string))
                throw new \InvalidArgumentException(
                    "HTTP message must be supplied as a string."
                );

            $name = preg_quote($name, "/");

            if (strpos($string, "\r\n\r\n") !== false)
                $string = strstr($string, "\r\n\r\n", true);

            $fields = explode("\r\n", $string);
            $return = false;

            foreach ($fields as $field) {
                if (
                    preg_match(
                        "/^($name):(.+)$/i",
                        $field,
                        $match
                    )
                ) {
                    $value = $match[2];
                    self::trim_whitespace($value);

                    if ($value != "")
                        $return = $value;
                }
            }

            return $return;
        }

        protected static function q_sort(
            $a,
            $b
        ): int {
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

        protected static function explode_preserve_quoted(
            $delimiter,
            $string
        ): ?array {
            if (str_contains($delimiter, "\""))
                return null;

            $string = str_replace(
                "\\\\",
                "\\\\".chr(31),
                $string
            );

            if (
                preg_match_all(
                    "/(?<!\\\\)\"/",
                    $string
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
                    $fixed[] = str_replace(
                        "\\\\".chr(31),
                        "\\\\",
                        $chunk
                    );
                    $chunk = "";
                } else {
                    $chunk.= $delimiter;
                }
            }

            return $fixed;
        }

        protected static function explode_preserve_comments(
            $delimiter,
            $string
        ): ?array {
            if (str_contains($delimiter, "("))
                return null;

            if (str_contains($delimiter, ")"))
                return null;

            $string = str_replace(
                "\\\\",
                "\\\\".chr(31),
                $string
            );

            if (
                preg_match_all(
                    "/(?<!\\\\)\(/",
                    $string,
                )
                !==
                preg_match_all(
                    "/(?<!\\\\)\)/",
                    $string,
                )
            )
                return null;

            $array = explode($delimiter, $string);
            $chunk = "";
            $fixed = array();

            foreach ($array as $value) {
                $chunk.= $value;

                if (
                    preg_match_all(
                        "/(?<!\\\\)\(/",
                        $chunk
                    )
                    ===
                    preg_match_all(
                        "/(?<!\\\\)\)/",
                        $chunk
                    )
                ) {
                    $fixed[] = str_replace(
                        "\\\\".chr(31),
                        "\\\\",
                        $chunk
                    );
                    $chunk = "";
                } else {
                    $chunk.= $delimiter;
                }
            }

            return $fixed;
        }

        protected static function parse_origin(
            $string
        ): ?array {
            if (
                !preg_match(
                    "/^[a-z]+:\/\/[a-z0-9\[\.][a-z0-9:\-\.]+?[a-z0-9\]\.](:[0-9]{1,5})?(\/|$)/i",
                    $string
                )
            )
                return null;

            $origin = parse_url($string);
            return ($origin !== false) ? $origin : null ;
        }

        protected static function rfc5322_date_immutable(
            $string
        ): \DateTimeImmutable|null {
            if (
                !preg_match(
                    "/^[A-Z][a-z]{2}, [0-9]{2} [A-Z][a-z]{2} [0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} GMT$/",
                    $string
                )
            )
                return null;

            $date = date_create_immutable($string);
            return ($date !== false) ? $date : null ;
        }

        protected static function trim_whitespace(
            &$mixed
        ): void {
            if (is_array($mixed)) {
                foreach ($mixed as &$item)
                    self::trim_whitespace($item);
            }

            if (is_string($mixed))
                $mixed = trim($mixed, " ");
        }

        protected static function filter_no_empty(
            &$array
        ): void {
            foreach ($array as &$value) {
                if (is_array($value))
                    self::filter_no_empty($value);
            }

            $array = array_filter(
                $array,
                function($value) {
                    return !(is_string($value) and $value == "");
                }
            );
        }

        public static function extract(
            $string
        ): array {
            if (!is_string($string))
                throw new \InvalidArgumentException(
                    "HTTP message must be supplied as a string."
                );

            if (strpos($string, "\r\n\r\n") !== false)
                $string = strstr($string, "\r\n\r\n", true);

            $fields = explode("\r\n", $string);
            $return = array();
            $reflect = new \ReflectionClass(self::class);
            $methods = $reflect->getMethods();

            foreach ($fields as $field) {
                $array = explode(":", $field, 2);

                if (count($array) < 2)
                    continue;

                if ($array[0] == "")
                    continue;

                if ($array[0] == "extract")
                    continue;

                $header = $array[0];
                $call = str_replace("-", "_", $header);
                $key = strtoupper($call);

                foreach ($methods as $method) {
                    if ($method->name != $call)
                        continue;

                    if (!$method->isPublic())
                        continue;

                    if (!$method->isStatic())
                        continue;

                    if (!isset($return[$key]))
                        $return[$key] = array();

                    $return[$key][] = self::$call($field);
                    break;
                }
            }

            return $return;
        }

        public static function Accept(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ACCEPT");
            else
                $value = self::header_from_string("Accept", $string);

            if ($value === false)
                return false;

            $types = explode(",", $value);
            self::trim_whitespace($types);
            self::filter_no_empty($types);

            if (empty($types))
                return null;

            usort($types, "self::q_sort");
            return $types;
        }

        public static function Accept_CH(
            $string
        ): array|null|false {
            $value = self::header_from_string("Accept-CH", $string);

            if ($value === false)
                return false;

            $hints = explode(",", $value);
            self::trim_whitespace($hints);
            self::filter_no_empty($hints);

            if (empty($hints))
                return null;

            return $hints;
        }

        public static function Accept_Charset(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ACCEPT_CHARSET");
            else
                $value = self::header_from_string("Accept-Charset", $string);

            if ($value === false)
                return false;

            $charsets = explode(",", $value);
            self::trim_whitespace($charsets);
            self::filter_no_empty($charsets);

            if (empty($charsets))
                return null;

            usort($charsets, "self::q_sort");
            return $charsets;
        }

        public static function Accept_Encoding(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ACCEPT_ENCODING");
            else
                $value = self::header_from_string("Accept-Encoding", $string);

            if ($value === false)
                return false;

            $encodings = explode(",", $value);
            self::trim_whitespace($encodings);
            self::filter_no_empty($encodings);

            if (empty($encodings))
                return null;

            usort($encodings, "self::q_sort");
            return $encodings;
        }

        public static function Accept_Language(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ACCEPT_LANGUAGE");
            else
                $value = self::header_from_string("Accept-Language", $string);

            if ($value === false)
                return false;

            $languages = explode(",", $value);
            self::trim_whitespace($languages);
            self::filter_no_empty($languages);

            if (empty($languages))
                return null;

            usort($languages, "self::q_sort");
            return $languages;
        }

        public static function Accept_Patch(
            $string
        ): array|null|false {
            $value = self::header_from_string("Accept-Patch", $string);

            if ($value === false)
                return false;

            $types = explode(",", $value);
            self::trim_whitespace($types);
            self::filter_no_empty($types);

            if (empty($types))
                return null;

            return $types;
        }

        public static function Accept_Post(
            $string
        ): array|null|false {
            $value = self::header_from_string("Accept-Post", $string);

            if ($value === false)
                return false;

            $types = explode(",", $value);
            self::trim_whitespace($types);
            self::filter_no_empty($types);

            if (empty($types))
                return null;

            return $types;
        }

        public static function Accept_Ranges(
            $string
        ): string|false {
            $value = self::header_from_string("Accept-Ranges", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Access_Control_Allow_Credentials(
            $string
        ): ?bool {
            $value = self::header_from_string("Access-Control-Allow-Credentials", $string);

            if ($value === false)
                return false;

            return ($value == "true") ? true : null ;
        }

        public static function Access_Control_Allow_Headers(
            $string
        ): array|null|false {
            $value = self::header_from_string("Access-Control-Allow-Headers", $string);

            if ($value === false)
                return false;

            $headers = explode(",", $value);
            self::trim_whitespace($headers);
            self::filter_no_empty($headers);

            if (empty($headers))
                return null;

            return $headers;
        }

        public static function Access_Control_Allow_Methods(
            $string
        ): array|null|false {
            $value = self::header_from_string("Access-Control-Allow-Methods", $string);

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

        public static function Access_Control_Allow_Origin(
            $string
        ): string|array|null|false {
            $value = self::header_from_string("Access-Control-Allow-Origin", $string);

            if ($value === false)
                return false;

            if ($value == "*")
                return $value;

            if ($value == "null")
                return null;

            return self::parse_origin($value);
        }

        public static function Access_Control_Expose_Headers(
            $string
        ): array|null|false {
            $value = self::header_from_string("Access-Control-Expose-Headers", $string);

            if ($value === false)
                return false;

            $headers = explode(",", $value);
            self::trim_whitespace($headers);
            self::filter_no_empty($headers);

            if (empty($headers))
                return null;

            return $headers;
        }

        public static function Access_Control_Max_Age(
            $string
        ): int|null|false {
            $value = self::header_from_string("Access-Control-Max-Age", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Access_Control_Request_Headers(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ACCESS_CONTROL_REQUEST_HEADERS");
            else
                $value = self::header_from_string("Access-Control-Request-Headers", $string);

            if ($value === false)
                return false;

            $headers = explode(",", $value);
            self::trim_whitespace($headers);
            self::filter_no_empty($headers);

            if (empty($headers))
                return null;

            return $headers;
        }

        public static function Access_Control_Request_Method(
            $string = null
        ): string|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ACCESS_CONTROL_REQUEST_METHOD");
            else
                $value = self::header_from_string("Access-Control-Request-Method", $string);

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

        public static function Age(
            $string
        ): int|null|false {
            $value = self::header_from_string("Age", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Allow(
            $string
        ): array|null|false {
            $value = self::header_from_string("Allow", $string);

            if ($value === false)
                return false;

            $methods = explode(",", $value);
            self::trim_whitespace($methods);
            self::filter_no_empty($methods);

            if (empty($methods))
                return null;

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

        public static function Alt_Svc(
            $string
        ): string|array|null|false {
            $value = self::header_from_string("Alt-Svc", $string);

            if ($value === false)
                return false;

            if ($value == "clear")
                return $value;

            $fields = self::explode_preserve_quoted(",", $value);

            if ($fields === null)
                return null;

            self::trim_whitespace($fields);
            self::filter_no_empty($fields);

            if (empty($fields))
                return null;

            $return = array();

            foreach ($fields as $field) {
                $params = self::explode_preserve_quoted(";", $field);

                if ($params === null)
                    return null;

                self::trim_whitespace($params);
                self::filter_no_empty($params);

                if (empty($params))
                    return null;

                $service = array(
                    "protocol" => null,
                    "host" => null,
                    "port" => null,
                    "ma" => null,
                    "persist" => false
                );

                foreach ($params as $index => $param) {
                    if (
                        !preg_match(
                            "/^([^=]+)=(.+)$/",
                            $param,
                            $match
                        )
                    )
                        return null;

                    $pn = $match[1];
                    $pv = $match[2];

                    if ($index == 0) {
                        if (preg_match("/^\".+\"$/", $pv))
                            $pv = stripslashes(trim($pv, "\""));

                        if (
                            !preg_match(
                                "/^([a-z0-9\[\.][a-z0-9:\-\.]+[a-z0-9\]\.])?(:([0-9]{1,5}))$/i",
                                $pv,
                                $authority,
                                PREG_UNMATCHED_AS_NULL
                            )
                        )
                            return null;

                        $service["protocol"] = rawurldecode($pn);
                        $service["host"] = $authority[1];
                        $service["port"] = $authority[3];
                    } else {
                        switch ($pn) {
                            case "ma":
                                if (
                                    !preg_match(
                                        "/^[0-9]+$/",
                                        $pv
                                    )
                                )
                                    return null;

                                $service["ma"] = intval($pv);
                                break;

                            case "persist":
                                $service["persist"] = ($pv == "1");
                                break;
                        }
                    }
                }

                $return[] = $service;
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function Alt_Used(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ALT_USED");
            else
                $value = self::header_from_string("Alt-Used", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^([a-z0-9\[\.][a-z0-9:\-\.]+?[a-z0-9\]\.])(:([0-9]{1,5}))?$/i",
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

        public static function Authorization(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_AUTHORIZATION");
            else
                $value = self::header_from_string("Authorization", $string);

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

        public static function Cache_Control(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_CACHE_CONTROL");
            else
                $value = self::header_from_string("Cache-Control", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            return $directives;
        }

        public static function Clear_Site_Data(
            $string
        ): array|null|false {
            $value = self::header_from_string("Clear-Site-Data", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            foreach ($directives as &$directive)
                $directive = stripslashes(trim($directive, "\""));

            return $directives;
        }

        public static function Connection(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_CONNECTION");
            else
                $value = self::header_from_string("Connection", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            return $directives;
        }

        public static function Content_Disposition(
            $string
        ): array|null|false {
            $value = self::header_from_string("Content-Disposition", $string);

            if ($value === false)
                return false;

            $params = self::explode_preserve_quoted(";", $value);

            if ($params === null)
                return null;

            self::trim_whitespace($params);
            self::filter_no_empty($params);

            if (empty($params))
                return null;

            $return = array("disposition" => array_shift($params));

            foreach ($params as $param) {
                if (
                    !preg_match(
                        "/^(name|filename\*?)=(\"(.+)\"|([^ ]+))$/",
                        $param,
                        $match,
                        PREG_UNMATCHED_AS_NULL
                    )
                )
                    return null;

                $return[$match[1]] = isset($match[3]) ?
                    stripslashes($match[3]) :
                    rawurldecode($match[4]) ;
            }

            return $return;
        }

        public static function Content_Encoding(
            $string
        ): array|null|false {
            $value = self::header_from_string("Content-Encoding", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

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

        public static function Content_Language(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_CONTENT_LANGUAGE");
            else
                $value = self::header_from_string("Content-Language", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            return $directives;
        }

        public static function Content_Length(
            $string = null
        ): int|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_CONTENT_LENGTH");
            else
                $value = self::header_from_string("Content-Length", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Content_Location(
            $string
        ): string|false {
            $value = self::header_from_string("Content-Location", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Content_Range(
            $string
        ): array|null|false {
            $value = self::header_from_string("Content-Range", $string);

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

        public static function Content_Security_Policy(
            $string
        ): array|null|false {
            $value = self::header_from_string("Content-Security-Policy", $string);

            if ($value === false)
                return false;

            $policy = explode(";", $value);
            self::trim_whitespace($policy);
            self::filter_no_empty($policy);

            if (empty($policy))
                return null;

            foreach ($policy as &$directive) {
                $params = explode(" ", $directive);
                $directive = array(array_shift($params), $params);

                foreach ($directive[1] as &$param) {
                    if (preg_match("/^'.+'$/", $param))
                        $param = trim($param, "'");
                }
            }

            self::trim_whitespace($policy);
            self::filter_no_empty($policy);
            return $policy;
        }

        public static function Content_Security_Policy_Report_Only(
            $string
        ): array|null|false {
            $value = self::header_from_string("Content-Security-Policy-Report-Only", $string);

            if ($value === false)
                return false;

            $policy = explode(";", $value);
            self::trim_whitespace($policy);
            self::filter_no_empty($policy);

            if (empty($policy))
                return null;

            foreach ($policy as &$directive) {
                $params = explode(" ", $directive);
                $directive = array(array_shift($params), $params);

                foreach ($directive[1] as &$param) {
                    if (preg_match("/^'.+'$/", $param))
                        $param = trim($param, "'");
                }
            }

            self::trim_whitespace($policy);
            self::filter_no_empty($policy);
            return $policy;
        }

        public static function Content_Type(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_CONTENT_TYPE");
            else
                $value = self::header_from_string("Content-Type", $string);

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

            self::trim_whitespace($return);
            return $return;
        }

        public static function Cookie(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_COOKIE");
            else
                $value = self::header_from_string("Cookie", $string);

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

                $cookies[] = array($match[1], $match[2]);
            }

            return $cookies;
        }

        public static function Cross_Origin_Embedder_Policy(
            $string
        ): string|null|false {
            $value = self::header_from_string("Cross-Origin-Embedder-Policy", $string);

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

        public static function Cross_Origin_Opener_Policy(
            $string
        ): string|null|false {
            $value = self::header_from_string("Cross-Origin-Opener-Policy", $string);

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

        public static function Cross_Origin_Resource_Policy(
            $string
        ): string|null|false {
            $value = self::header_from_string("Cross-Origin-Resource-Policy", $string);

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

        public static function Date(
            $string = null
        ): \DateTimeImmutable|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_DATE");
            else
                $value = self::header_from_string("Date", $string);

            if ($value === false)
                return false;

            return self::rfc5322_date_immutable($value);
        }

        public static function Device_Memory(
            $string = null
        ): float|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_DEVICE_MEMORY");
            else
                $value = self::header_from_string("Device-Memory", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9\.]+$/",
                $value
            ) ? floatval($value) : null ;
        }

        public static function Digest(
            $string
        ): array|null|false {
            $value = self::header_from_string("Digest", $string);

            if ($value === false)
                return false;

            $params = explode(",", $value);
            self::trim_whitespace($params);
            self::filter_no_empty($params);

            if (empty($params))
                return null;

            $return = array();

            foreach ($params as $param) {
                if (
                    !preg_match(
                        "/^([^=]+)=(.+)$/",
                        $param,
                        $match
                    )
                )
                    return null;

                $return[] = array($match[1], $match[2]);
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function Downlink(
            $string = null
        ): float|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_DOWNLINK");
            else
                $value = self::header_from_string("Downlink", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9\.]+$/",
                $value
            ) ? floatval($value) : null ;
        }

        public static function DNT(
            $string = null
        ): int|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_DNT");
            else
                $value = self::header_from_string("DNT", $string);

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

        public static function ECT(
            $string = null
        ): string|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ECT");
            else
                $value = self::header_from_string("ECT", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "slow-2g":
                case "2g":
                case "3g":
                case "4g":
                    return $value;
                default:
                    return null;
            }
        }

        public static function ETag(
            $string
        ): string|null|false {
            $value = self::header_from_string("ETag", $string);

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

        public static function Expect(
            $string = null
        ): int|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_EXPECT");
            else
                $value = self::header_from_string("Expect", $string);

            if ($value === false)
                return false;

            return ($value == "100-continue") ? 100 : null ;
        }

        public static function Expires(
            $string
        ): \DateTimeImmutable|null|false {
            $value = self::header_from_string("Expires", $string);

            if ($value === false)
                return false;

            return self::rfc5322_date_immutable($value);
        }

        public static function Forwarded(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_FORWARDED");
            else
                $value = self::header_from_string("Forwarded", $string);

            if ($value === false)
                return false;

            $fields = self::explode_preserve_quoted(",", $value);

            if ($fields === null)
                return null;

            self::trim_whitespace($fields);
            self::filter_no_empty($fields);

            if (empty($fields))
                return null;

            $return = array();

            foreach ($fields as $field) {
                $params = self::explode_preserve_quoted(";", $field);

                if ($params === null)
                    return null;

                self::trim_whitespace($params);
                self::filter_no_empty($params);

                if (empty($params))
                    return null;

                $directive = array();

                foreach ($params as $param) {
                    if (
                        !preg_match(
                            "/^(by|for|host|proto)=(.+)$/i",
                            $param,
                            $match
                        )
                    )
                        return null;

                    $pn = strtolower($match[1]);
                    $pv = trim($match[2], " ");

                    if (preg_match("/^\".+\"$/", $pv))
                        $pv = stripslashes(trim($pv, "\""));

                    $directive[$pn] = $pv;
                }

                $return[] = $directive;
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function From(
            $string = null
        ): string|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_FROM");
            else
                $value = self::header_from_string("From", $string);

            if ($value === false)
                return false;

            if (
                preg_match(
                    "/^([a-z0-9!#$%&'*+-\/=?^_`{}|~\.]+|\".+\")@[a-z0-9\-\.\[\]:]+$/i",
                    $value
                )
            )
                return $value;

            if (
                preg_match(
                    "/<([a-z0-9!#$%&'*+-\/=?^_`{}|~\.]+|\".+\")@[a-z0-9\-\.\[\]:]+>/i",
                    $value
                )
            )
                return $value;

            return null;
        }

        public static function Host(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_HOST");
            else
                $value = self::header_from_string("Host", $string);

            if ($value === false)
                return false;

            if (
                !preg_match(
                    "/^([a-z0-9\[\.][a-z0-9:\-\.]+?[a-z0-9\]\.])(:([0-9]{1,5}))?$/i",
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

        public static function If_Match(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_IF_MATCH");
            else
                $value = self::header_from_string("If-Match", $string);

            if ($value === false)
                return false;

            $etags = self::explode_preserve_quoted(",", $value);

            if ($etags === null)
                return null;

            self::trim_whitespace($etags);
            self::filter_no_empty($etags);

            if (empty($etags))
                return null;

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

        public static function If_Modified_Since(
            $string = null
        ): \DateTimeImmutable|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_IF_MODIFIED_SINCE");
            else
                $value = self::header_from_string("If-Modified-Since", $string);

            if ($value === false)
                return false;

            return self::rfc5322_date_immutable($value);
        }

        public static function If_None_Match(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_IF_NONE_MATCH");
            else
                $value = self::header_from_string("If-None-Match", $string);

            if ($value === false)
                return false;

            $etags = self::explode_preserve_quoted(",", $value);

            if ($etags === null)
                return null;

            self::trim_whitespace($etags);
            self::filter_no_empty($etags);

            if (empty($etags))
                return null;

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

        public static function If_Range(
            $string = null
        ): \DateTimeImmutable|string|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_IF_RANGE");
            else
                $value = self::header_from_string("If-Range", $string);

            if ($value === false)
                return false;

            if (
                preg_match(
                    "/^(W\/)?\".+\"$/",
                    $value
                )
            )
                return stripslashes($value);

            return self::rfc5322_date_immutable($value);
        }

        public static function If_Unmodified_Since(
            $string = null
        ): \DateTimeImmutable|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_IF_UNMODIFIED_SINCE");
            else
                $value = self::header_from_string("If-Unmodified-Since", $string);

            if ($value === false)
                return false;

            return self::rfc5322_date_immutable($value);
        }

        public static function Keep_Alive(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_KEEP_ALIVE");
            else
                $value = self::header_from_string("Keep-Alive", $string);

            if ($value === false)
                return false;

            $params = explode(",", $value);
            self::trim_whitespace($params);
            self::filter_no_empty($params);

            if (empty($params))
                return null;

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

            self::trim_whitespace($return);
            return $return;
        }

        public static function Last_Modified(
            $string
        ): \DateTimeImmutable|null|false {
            $value = self::header_from_string("Last-Modified", $string);

            if ($value === false)
                return false;

            return self::rfc5322_date_immutable($value);
        }

        public static function Link(
            $string
        ): array|null|false {
            $value = self::header_from_string("Link", $string);

            if ($value === false)
                return false;

            $fields = self::explode_preserve_quoted(",", $value);

            if ($fields === null)
                return null;

            self::trim_whitespace($fields);
            self::filter_no_empty($fields);

            if (empty($fields))
                return null;

            $return = array();

            foreach ($fields as $field) {
                $params = self::explode_preserve_quoted(";", $field);

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
                            "/^([^=]+)=(.+)$/",
                            $param,
                            $match
                        )
                    )
                        return null;

                    $pn = $match[1];
                    $pv = trim($match[2], " ");

                    if (preg_match("/^\".+\"$/", $pv))
                        $pv = stripslashes(trim($pv, "\""));

                    $directive[1][] = array($pn, $pv);
                }

                $return[] = $directive;
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function Location(
            $string
        ): string|false {
            $value = self::header_from_string("Location", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Max_Forwards(
            $string = null
        ): int|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_MAX_FORWARDS");
            else
                $value = self::header_from_string("Max-Forwards", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Origin(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_ORIGIN");
            else
                $value = self::header_from_string("Origin", $string);

            if ($value === false)
                return false;

            return self::parse_origin($value);
        }

        public static function Permissions_Policy(
            $string
        ): array|null|false {
            $value = self::header_from_string("Permissions-Policy", $string);

            if ($value === false)
                return false;

            $policies = self::explode_preserve_quoted(",", $value);

            if ($policies === null)
                return null;

            self::trim_whitespace($policies);
            self::filter_no_empty($policies);

            if (empty($policies))
                return null;

            foreach ($policies as &$policy) {
                if (
                    !preg_match(
                        "/^([a-zA-Z0-9\-]+)=(\*|\(([^)]*)\))$/",
                        $policy,
                        $match
                    )
                )
                    return null;

                $directive = $match[1];
                $allowlist = self::explode_preserve_quoted(
                    " ",
                    isset($match[3]) ? $match[3] : $match[2]
                );

                if ($allowlist === null)
                    return null;

                switch ($directive) {
                    case "accelerometer":
                    case "ambient-light-sensor":
                    case "autoplay":
                    case "battery":
                    case "camera":
                    case "display-capture":
                    case "document-domain":
                    case "encrypted-media":
                    case "execution-while-not-rendered":
                    case "execution-while-out-of-viewport":
                    case "fullscreen":
                    case "gamepad":
                    case "geolocation":
                    case "gyroscope":
                    case "hid":
                    case "identity-credentials-get":
                    case "idle-detection":
                    case "local-fonts":
                    case "magnetometer":
                    case "microphone":
                    case "midi":
                    case "payment":
                    case "picture-in-picture":
                    case "publickey-credentials-create":
                    case "publickey-credentials-get":
                    case "screen-wake-lock":
                    case "serial":
                    case "speaker-selection":
                    case "storage-access":
                    case "usb":
                    case "web-share":
                    case "xr-spatial-tracking":
                        break;
                    default:
                        return null;
                }

                foreach ($allowlist as &$origin) {
                    if (preg_match("/^\".+\"$/", $origin))
                        $origin = stripslashes(trim($origin, "\""));
                }

                $policy = array($directive, $allowlist);
            }

            self::trim_whitespace($policies);
            self::filter_no_empty($policies);
            return $policies;
        }

        public static function Pragma(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_PRAGMA");
            else
                $value = self::header_from_string("Pragma", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            return $directives;
        }

        public static function Proxy_Authenticate(
            $string
        ): array|null|false {
            $value = self::header_from_string("Proxy-Authenticate", $string);

            if ($value === false)
                return false;

            $fields = self::explode_preserve_quoted(",", $value);

            if ($fields === null)
                return null;

            self::trim_whitespace($fields);
            self::filter_no_empty($fields);

            if (empty($fields))
                return null;

            $challenges = array();
            $count = 0;

            foreach ($fields as $field) {
                if (
                    preg_match(
                        "/^[a-zA-Z0-9_\-]+( +[^=]|$)/",
                        $field
                    )
                ) {
                    if (isset($challenges[$count]))
                        $count++;

                    $params = self::explode_preserve_quoted(" ", $field);

                    if ($params === null)
                        return null;

                    self::trim_whitespace($params);
                    self::filter_no_empty($params);

                    $challenges[$count] = $params;
                } else {
                    if (!isset($challenges[$count]))
                        return null;

                    $challenges[$count][] = $field;
                }
            }

            return $challenges;
        }

        public static function Proxy_Authorization(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_PROXY_AUTHORIZATION");
            else
                $value = self::header_from_string("Proxy-Authorization", $string);

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

        public static function Range(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_RANGE");
            else
                $value = self::header_from_string("Range", $string);

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

        public static function Referer(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_REFERER");
            else
                $value = self::header_from_string("Referer", $string);

            if ($value === false)
                return false;

            return self::parse_origin($value);
        }

        public static function Referrer_Policy(
            $string
        ): string|null|false {
            $value = self::header_from_string("Referrer-Policy", $string);

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

        public static function Retry_After(
            $string
        ): \DateTimeImmutable|int|null|false {
            $value = self::header_from_string("Retry-After", $string);

            if ($value === false)
                return false;

            if (
                preg_match(
                    "/^[0-9]+$/",
                    $value
                )
            )
                return intval($value);

            return self::rfc5322_date_immutable($value);
        }

        public static function RTT(
            $string = null
        ): int|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_RTT");
            else
                $value = self::header_from_string("RTT", $string);

            if ($value === false)
                return false;

            return preg_match(
                "/^[0-9]+$/",
                $value
            ) ? intval($value) : null ;
        }

        public static function Save_Data(
            $string = null
        ): int|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_SAVE_DATA");
            else
                $value = self::header_from_string("Save-Data", $string);

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

        public static function Sec_Fetch_Dest(
            $string = null
        ): string|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_SEC_FETCH_DEST");
            else
                $value = self::header_from_string("Sec-Fetch-Dest", $string);

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

        public static function Sec_Fetch_Mode(
            $string = null
        ): string|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_SEC_FETCH_MODE");
            else
                $value = self::header_from_string("Sec-Fetch-Mode", $string);

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

        public static function Sec_Fetch_Site(
            $string = null
        ): string|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_SEC_FETCH_SITE");
            else
                $value = self::header_from_string("Sec-Fetch-Site", $string);

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

        public static function Sec_Fetch_User(
            $string = null
        ): ?bool {
            if (!isset($string))
                $value = self::header_from_server("HTTP_SEC_FETCH_USER");
            else
                $value = self::header_from_string("Sec-Fetch-User", $string);

            if ($value === false)
                return false;

            return ($value === "?1") ? true : null ;
        }

        public static function Sec_GPC(
            $string = null
        ): ?bool {
            if (!isset($string))
                $value = self::header_from_server("HTTP_SEC_GPC");
            else
                $value = self::header_from_string("Sec-GPC", $string);

            if ($value === false)
                return false;

            return ($value == "1") ? true : null ;
        }

        public static function Sec_Purpose(
            $string = null
        ): string|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_SEC_PURPOSE");
            else
                $value = self::header_from_string("Sec-Purpose", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "prefetch":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Server(
            $string
        ): array|null|false {
            $value = self::header_from_string("Server", $string);

            if ($value === false)
                return false;

            $directives = self::explode_preserve_comments(" ", $value);

            if ($directives === null)
                return null;

            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            $identifiers = array();
            $count = 0;

            foreach ($directives as $directive) {
                if (
                    preg_match(
                        "/^([^\/()]+)(\/([0-9\.]+))?$/",
                        $directive,
                        $match
                    )
                ) {
                    if (isset($identifiers[$count]))
                        $count++;

                    $identifiers[$count] = array("product" => $match[1]);

                    if (isset($match[3]))
                        $identifiers[$count]["version"] = $match[3];
                } else {
                    if (!isset($identifiers[$count]))
                        return null;

                    if (
                        !preg_match(
                            "/^\((.+)\)$/",
                            $directive,
                            $match
                        )
                    )
                        return null;

                    $identifiers[$count]["comment"] = stripslashes($match[1]);
                    $count++;
                }
            }

            return $identifiers;
        }

        public static function Server_Timing(
            $string
        ): array|null|false {
            $value = self::header_from_string("Server-Timing", $string);

            if ($value === false)
                return false;

            $fields = self::explode_preserve_quoted(",", $value);

            if ($fields === null)
                return null;

            self::trim_whitespace($fields);
            self::filter_no_empty($fields);

            if (empty($fields))
                return null;

            $return = array();

            foreach ($fields as $field) {
                $params = self::explode_preserve_quoted(";", $field);

                if ($params === null)
                    return null;

                self::trim_whitespace($params);
                self::filter_no_empty($params);

                if (empty($params))
                    return null;

                $metric = array("name" => array_shift($params));

                foreach ($params as $param) {
                    if (
                        !preg_match(
                            "/^(desc|dur)=(.+)$/",
                            $param,
                            $match
                        )
                    )
                        return null;

                    $pn = $match[1];
                    $pv = trim($match[2], " ");

                    if (preg_match("/^\".+\"$/", $pv))
                        $pv = stripslashes(trim($pv, "\""));

                    $metric[$pn] = $pv;
                }

                $return[] = $metric;
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function Service_Worker_Navigation_Preload(
            $string = null
        ): string|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_SERVICE_WORKER_NAVIGATION_PRELOAD");
            else
                $value = self::header_from_string("Service-Worker-Navigation-Preload", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Set_Cookie(
            $string
        ): array|null|false {
            $value = self::header_from_string("Set-Cookie", $string);

            if ($value === false)
                return false;

            $params = self::explode_preserve_quoted(";", $value);

            if ($params === null)
                return null;

            self::trim_whitespace($params);
            self::filter_no_empty($params);

            if (empty($params))
                return null;

            $return = array(
                array(),
                array(
                    "Path" => null,
                    "Domain" => null,
                    "SameSite" => null,
                    "Expires" => null,
                    "Max-Age" => null,
                    "HttpOnly" => false,
                    "Secure" => false,
                    "Partitioned" => false,
                )
            );

            foreach ($params as $index => $param) {
                if ($index == 0) {
                    if (
                        !preg_match(
                            "/^([^()<>@,;:\\\\ \"\/\[\]\?={}\t]+)=\"?([^\",;\\\\]+)\"?$/",
                            $param,
                            $match
                        )
                    )
                        return null;

                    $return[0] = array($match[1], $match[2]);
                } else {
                    if (
                        preg_match(
                            "/^([a-zA-Z0-9\-]+)=(.+)$/",
                            $param,
                            $match
                        )
                    ) {
                        $pn = $match[1];
                        $pv = trim($match[2], " ");

                        if (!strlen($pv))
                            return null;

                        switch ($pn) {
                            case "Path":
                            case "Domain":
                                break;

                            case "SameSite":
                                switch ($pv) {
                                    case "Strict":
                                    case "Lax":
                                    case "None":
                                        break;
                                    default:
                                        return null;
                                }

                                break;

                            case "Expires":
                                if (!$pv = self::rfc5322_date_immutable($pv))
                                    return null;

                                break;

                            case "Max-Age":
                                if (
                                    !preg_match(
                                        "/^-?[0-9]+$/",
                                        $pv
                                    )
                                )
                                    return null;

                                $pv = intval($pv);
                                break;

                            default:
                                return null;
                        }

                        $return[1][$pn] = $pv;
                    } else {
                        switch ($param) {
                            case "HttpOnly":
                            case "Secure":
                            case "Partitioned":
                                break;
                            default:
                                return null;
                        }

                        $return[1][$param] = true;
                    }
                }
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function Set_Login(
            $string
        ): string|null|false {
            $value = self::header_from_string("Set-Login", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "logged-in":
                case "logged-out":
                    return $value;
                default:
                    return null;
            }
        }

        public static function SourceMap(
            $string
        ): string|false {
            $value = self::header_from_string("SourceMap", $string);

            if ($value === false)
                return false;

            return $value;
        }

        public static function Strict_Transport_Security(
            $string
        ): array|null|false {
            $value = self::header_from_string("Strict-Transport-Security", $string);

            if ($value === false)
                return false;

            $params = explode(";", $value);
            self::trim_whitespace($params);
            self::filter_no_empty($params);

            if (empty($params))
                return null;

            $return = array(
                "max-age" => null,
                "includeSubDomains" => false,
                "preload" => false
            );

            foreach ($params as $param) {
                if (
                    preg_match(
                        "/^(max-age)=([0-9]+)$/",
                        $param,
                        $match
                    )
                ) {
                    $return["max-age"] = intval($match[2]);
                } else {
                    switch ($param) {
                        case "includeSubDomains":
                        case "preload":
                            $return[$param] = true;
                            break;
                        default:
                            return null;
                    }
                }
            }

            if (!isset($return["max-age"]))
                return null;

            return $return;
        }

        public static function TE(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_TE");
            else
                $value = self::header_from_string("TE", $string);

            if ($value === false)
                return false;

            $encodings = explode(",", $value);
            self::trim_whitespace($encodings);
            self::filter_no_empty($encodings);

            if (empty($encodings))
                return null;

            usort($encodings, "self::q_sort");
            return $encodings;
        }

        public static function Timing_Allow_Origin(
            $string
        ): string|array|null|false {
            $value = self::header_from_string("Timing-Allow-Origin", $string);

            if ($value === false)
                return false;

            $origins = explode(",", $value);
            self::trim_whitespace($origins);
            self::filter_no_empty($origins);

            if (empty($origins))
                return null;

            foreach ($origins as &$origin) {
                if ($origin == "*")
                    return $origin;

                $origin = self::parse_origin($origin);
            }

            return $origins;
        }

        public static function Tk(
            $string
        ): string|null|false {
            $value = self::header_from_string("Tk", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "!":
                case "?":
                case "G":
                case "N":
                case "T":
                case "C":
                case "P":
                case "D":
                case "U":
                    return $value;
                default:
                    return null;
            }
        }

        public static function Trailer(
            $string
        ): array|null|false {
            $value = self::header_from_string("Trailer", $string);

            if ($value === false)
                return false;

            $protocols = explode(",", $value);
            self::trim_whitespace($protocols);
            self::filter_no_empty($protocols);

            if (empty($protocols))
                return null;

            return $protocols;
        }

        public static function Transfer_Encoding(
            $string
        ): array|null|false {
            $value = self::header_from_string("Transfer-Encoding", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            foreach ($directives as $directive) {
                switch ($directive) {
                    case "gzip":
                    case "compress":
                    case "deflate":
                    case "chunked":
                        break;
                    default:
                        return null;
                }
            }

            return $directives;
        }

        public static function Upgrade(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_UPGRADE");
            else
                $value = self::header_from_string("Upgrade", $string);

            if ($value === false)
                return false;

            $protocols = explode(",", $value);
            self::trim_whitespace($protocols);
            self::filter_no_empty($protocols);

            if (empty($protocols))
                return null;

            return $protocols;
        }

        public static function Upgrade_Insecure_Requests(
            $string = null
        ): int|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_UPGRADE_INSECURE_REQUESTS");
            else
                $value = self::header_from_string("Upgrade-Insecure-Requests", $string);

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

        public static function User_Agent(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_USER_AGENT");
            else
                $value = self::header_from_string("User-Agent", $string);

            if ($value === false)
                return false;

            $directives = self::explode_preserve_comments(" ", $value);

            if ($directives === null)
                return null;

            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            $identifiers = array();
            $count = 0;

            foreach ($directives as $directive) {
                if (
                    preg_match(
                        "/^([^\/()]+)(\/([0-9\.]+))?$/",
                        $directive,
                        $match
                    )
                ) {
                    if (isset($identifiers[$count]))
                        $count++;

                    $identifiers[$count] = array("product" => $match[1]);

                    if (isset($match[3]))
                        $identifiers[$count]["version"] = $match[3];
                } else {
                    if (!isset($identifiers[$count]))
                        return null;

                    if (
                        !preg_match(
                            "/^\((.+)\)$/",
                            $directive,
                            $match
                        )
                    )
                        return null;

                    $identifiers[$count]["comment"] = stripslashes($match[1]);
                    $count++;
                }
            }

            return $identifiers;
        }

        public static function Vary(
            $string
        ): string|array|null|false {
            $value = self::header_from_string("Vary", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            foreach ($directives as $directive) {
                if ($directive == "*")
                    return $directive;
            }

            return $directives;
        }

        public static function Via(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_VIA");
            else
                $value = self::header_from_string("Via", $string);

            if ($value === false)
                return false;

            $directives = self::explode_preserve_comments(",", $value);

            if ($directives === null)
                return null;

            self::trim_whitespace($directives);
            self::filter_no_empty($directives);

            if (empty($directives))
                return null;

            $proxies = array();

            foreach ($directives as $directive) {
                if (
                    !preg_match(
                        "/^(([^ \/:()]+)\/)?([0-9\.]+) +([^ \/:()]+)(:([0-9]+))?( +\((.+)\))?$/",
                        $directive,
                        $match,
                        PREG_UNMATCHED_AS_NULL
                    )
                )
                    return null;

                $proxy = array();

                if (isset($match[2]))
                    $proxy["protocol"] = $match[2];

                $proxy["version"] = $match[3];
                $proxy["pseudonym"] = $match[4];

                if (isset($match[6]))
                    $proxy["port"] = $match[6];

                if (isset($match[8]))
                    $proxy["comment"] = stripslashes($match[8]);

                $proxies[] = $proxy;
            }

            return $proxies;
        }

        public static function Want_Digest(
            $string = null
        ): array|null|false {
            if (!isset($string))
                $value = self::header_from_server("HTTP_WANT_DIGEST");
            else
                $value = self::header_from_string("Want-Digest", $string);

            if ($value === false)
                return false;

            $algorithms = explode(",", $value);
            self::trim_whitespace($algorithms);
            self::filter_no_empty($algorithms);

            if (empty($algorithms))
                return null;

            usort($algorithms, "self::q_sort");
            return $algorithms;
        }

        public static function WWW_Authenticate(
            $string
        ): array|null|false {
            $value = self::header_from_string("WWW-Authenticate", $string);

            if ($value === false)
                return false;

            $fields = self::explode_preserve_quoted(",", $value);

            if ($fields === null)
                return null;

            self::trim_whitespace($fields);
            self::filter_no_empty($fields);

            if (empty($fields))
                return null;

            $challenges = array();
            $count = 0;

            foreach ($fields as $field) {
                if (
                    preg_match(
                        "/^[a-zA-Z0-9_\-]+( +[^=]|$)/",
                        $field
                    )
                ) {
                    if (isset($challenges[$count]))
                        $count++;

                    $params = self::explode_preserve_quoted(" ", $field);

                    if ($params === null)
                        return null;

                    self::trim_whitespace($params);
                    self::filter_no_empty($params);

                    $challenges[$count] = $params;
                } else {
                    if (!isset($challenges[$count]))
                        return null;

                    $challenges[$count][] = $field;
                }
            }

            return $challenges;
        }

        public static function X_Content_Type_Options(
            $string
        ): string|null|false {
            $value = self::header_from_string("X-Content-Type-Options", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "nosniff":
                    return $value;
                default:
                    return null;
            }
        }

        public static function X_Frame_Options(
            $string
        ): string|null|false {
            $value = self::header_from_string("X-Frame-Options", $string);

            if ($value === false)
                return false;

            switch ($value) {
                case "DENY":
                case "SAMEORIGIN":
                    return $value;
                default:
                    return null;
            }
        }
    }
