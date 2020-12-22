<?php
    class HTTPHeader {
        const HTTPHEADER_VERSION_MAJOR = 0;
        const HTTPHEADER_VERSION_MINOR = 1;

        private static function header_extract($name, $string) {
            if (!is_string($string))
                throw new Exception("HTTP header must be a string.");

            if (preg_match("/^$name: (.+?)(\r\n)?$/", $string, $match))
                return $match[1];

            return false;
        }

        private static function header_request($name) {
            return isset($_SERVER[$name]) ? $_SERVER[$name] : false ;
        }

        private static function trim_whitespace(&$mixed) {
            if (is_array($mixed)) {
                foreach ($mixed as &$item)
                    self::trim_whitespace($item);
            }

            if (is_string($mixed))
                $mixed = trim($mixed, " ");
        }

        private static function q_sort($a, $b) {
            $a_q = preg_match("/;q=([0-9\.]+)$/", $a, $a_match) ?
                floatval($a_match[1]) : 1.0 ;

            $b_q = preg_match("/;q=([0-9\.]+)$/", $b, $b_match) ?
                floatval($b_match[1]) : 1.0 ;

            if ($a_q == $b_q)
                return 0;

            return ($a_q > $b_q) ? -1 : 1 ;
        }

        public static function Accept($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCEPT");
            else
                $value = self::header_extract("Accept", $string);

            if ($value === false)
                return false;

            $types = explode(",", $value);
            self::trim_whitespace($types);
            usort($types, "self::q_sort");
            return $types;
        }

        public static function Accept_Charset($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCEPT_CHARSET");
            else
                $value = self::header_extract("Accept-Charset", $string);

            if ($value === false)
                return false;

            $charsets = explode(",", $value);
            self::trim_whitespace($charsets);
            usort($charsets, "self::q_sort");
            return $charsets;
        }

        public static function Accept_Encoding($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCEPT_ENCODING");
            else
                $value = self::header_extract("Accept-Encoding", $string);

            if ($value === false)
                return false;

            $encodings = explode(",", $value);
            self::trim_whitespace($encodings);
            usort($encodings, "self::q_sort");
            return $encodings;
        }

        public static function Accept_Language($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_ACCEPT_LANGUAGE");
            else
                $value = self::header_extract("Accept-Language", $string);

            if ($value === false)
                return false;

            $languages = explode(",", $value);
            self::trim_whitespace($languages);
            usort($languages, "self::q_sort");
            return $languages;
        }

        public static function Authorization($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_AUTHORIZATION");
            else
                $value = self::header_extract("Authorization", $string);

            if ($value === false)
                return false;

            $array = explode(" ", $value, 2);

            if (count($array) < 2)
                return false;

            return array($array[0], $array[1]);
        }

        public static function Cache_Control($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_CACHE_CONTROL");
            else
                $value = self::header_extract("Cache-Control", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            return $directives;
        }

        public static function Connection($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_CONNECTION");
            else
                $value = self::header_extract("Connection", $string);

            if ($value === false)
                return false;

            $directives = explode(",", $value);
            self::trim_whitespace($directives);
            return $directives;
        }

        public static function Content_Type($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_CONTENT_TYPE");
            else
                $value = self::header_extract("Content-Type", $string);

            if ($value === false)
                return false;

            $params = explode(";", $value);
            $return = array();

            foreach ($params as $param) {
                if (preg_match("/(charset|boundary)=([^=]+)/", $param, $match))
                    $return[$match[1]] = $match[2];
                else
                    $return["type"] = $param;
            }

            return $return;
        }

        public static function Cookie($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_COOKIE");
            else
                $value = self::header_extract("Cookie", $string);

            if ($value === false)
                return false;

            $pairs = explode("; ", $value);
            $cookies = array();

            foreach ($pairs as $pair) {
                if (!preg_match("/([^=]+)=([^=]+)/", $pair, $match))
                    return false;

                $cookies[str_replace(".", "_", $match[1])] = $match[2];
            }

            return $cookies;
        }

        public static function Date($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_DATE");
            else
                $value = self::header_extract("Date", $string);

            if ($value === false)
                return false;

            $date = new DateTimeImmutable($value);
            return $date;
        }

        public static function DNT($string = null) {
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

        public static function Forwarded($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_FORWARDED");
            else
                $value = self::header_extract("Forwarded", $string);

            if ($value === false)
                return false;

            $fields = explode(",", $value);
            $return = array();

            foreach ($fields as $field) {
                $parts = explode(";", $field);
                $directive = array();

                foreach ($parts as $part) {
                    if (!preg_match("/(by|for|host|proto)=([^=]+)/i", $part, $match))
                        return false;

                    $directive[strtolower($match[1])] = $match[2];
                }

                $return[] = $directive;
            }

            self::trim_whitespace($return);
            return $return;
        }

        public static function From($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_FROM");
            else
                $value = self::header_extract("From", $string);

            if ($value === false)
                return false;

            if (!preg_match("/[^@ ]+@[^@ ]+/", $value))
                return false;

            self::trim_whitespace($value);
            return $value;
        }

        public static function Host($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_HOST");
            else
                $value = self::header_extract("Host", $string);

            if ($value === false)
                return false;

            if (!preg_match("/^(.+?)(:([0-9]+))?$/", $value, $match))
                return false;

            $return = array("host" => $match[1]);

            if (isset($match[3]))
                $return["port"] = $match[3];

            return $return;
        }

        public static function If_Match($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_MATCH");
            else
                $value = self::header_extract("If-Match", $string);

            if ($value === false)
                return false;

            $etags = explode(",", $value);
            self::trim_whitespace($etags);
            return $etags;
        }

        public static function If_Modified_Since($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_MODIFIED_SINCE");
            else
                $value = self::header_extract("If-Modified-Since", $string);

            if ($value === false)
                return false;

            $date = new DateTimeImmutable($value);
            return $date;
        }

        public static function If_None_Match($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_NONE_MATCH");
            else
                $value = self::header_extract("If-None-Match", $string);

            if ($value === false)
                return false;

            $etags = explode(",", $value);
            self::trim_whitespace($etags);
            return $etags;
        }

        public static function If_Range($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_RANGE");
            else
                $value = self::header_extract("If-Range", $string);

            if ($value === false)
                return false;

            $date = new DateTimeImmutable($value);

            if ($date !== false)
                return $date;

            if (preg_match("/^(W\/)?\".+\"$/", $value))
                return $value;

            return false;
        }

        public static function If_Unmodified_Since($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_IF_UNMODIFIED_SINCE");
            else
                $value = self::header_extract("If-Unmodified-Since", $string);

            if ($value === false)
                return false;

            $date = new DateTimeImmutable($value);
            return $date;
        }

        public static function Keep_Alive($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_KEEP_ALIVE");
            else
                $value = self::header_extract("Keep-Alive", $string);

            if ($value === false)
                return false;

            $params = explode(",", $value);
            $return = array();

            foreach ($params as $param) {
                if (!preg_match("/(timeout|max)=([^=]+)/", $param, $match))
                    return false;

                $return[$match[1]] = $match[2];
            }

            return $return;
        }

        public static function Origin($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_ORIGIN");
            else
                $value = self::header_extract("Origin", $string);

            if ($value === false)
                return false;

            $origin = parse_url($value);
            return $origin;
        }

        public static function Proxy_Authorization($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_PROXY_AUTHORIZATION");
            else
                $value = self::header_extract("Proxy-Authorization", $string);

            if ($value === false)
                return false;

            $array = explode(" ", $value, 2);

            if (count($array) < 2)
                return false;

            return array($array[0], $array[1]);
        }

        public static function Range($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_RANGE");
            else
                $value = self::header_extract("Range", $string);

            if ($value === false)
                return false;

            if (!preg_match("/^([a-zA-Z0-9]+)=(.+)$/", $value, $match))
                return false;

            $ranges = explode(",", $match[2]);
            $return = array("unit" => $match[1],
                            "ranges" => $ranges);

            self::trim_whitespace($return);
            return $return;
        }

        public static function Referer($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_REFERER");
            else
                $value = self::header_extract("Referer", $string);

            if ($value === false)
                return false;

            $referer = parse_url($value);
            return $referer;
        }

        public static function Save_Data($string = null) {
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

        public static function TE($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_TE");
            else
                $value = self::header_extract("TE", $string);

            if ($value === false)
                return false;

            $encodings = explode(",", $value);
            self::trim_whitespace($encodings);
            usort($encodings, "self::q_sort");
            return $encodings;
        }

        public static function Upgrade_Insecure_Requests($string = null) {
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

        public static function User_Agent($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_USER_AGENT");
            else
                $value = self::header_extract("User-Agent", $string);

            if ($value === false)
                return false;

            if (!preg_match("/^([^\/]+)\/([0-9\.]+) (.+)$/", $value, $match))
                return false;

            $return = array("product" => $match[1],
                            "version" => $match[2],
                            "comment" => $match[3]);

            return $return;
        }

        public static function Via($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_VIA");
            else
                $value = self::header_extract("Via", $string);

            if ($value === false)
                return false;

            $proxies = explode(",", $value);
            self::trim_whitespace($proxies);
            return $proxies;
        }

        public static function Want_Digest($string = null) {
            if (!isset($string))
                $value = self::header_request("HTTP_WANT_DIGEST");
            else
                $value = self::header_extract("Want-Digest", $string);

            if ($value === false)
                return false;

            $algorithms = explode(",", $value);
            self::trim_whitespace($algorithms);
            usort($algorithms, "self::q_sort");
            return $algorithms;
        }
    }
