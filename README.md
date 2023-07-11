## What is this?

HTTPHeader is a PHP class for inspecting HTTP headers.

## Requirements

* PHP 8.0+

## Usage

Methods for inspecting response headers must be supplied with a string containing a single header (with or without field name) or a complete HTTP response. Methods for inspecting request headers can optionally be supplied with a string containing a single header (with or without field name) or a complete HTTP request; alternatively these methods will attempt to read the value for the current request from the `$_SERVER` superglobal if not supplied with a string. Methods will return `false` if the header is not present or cannot be parsed.

### `HTTPHeader::Accept($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => text/html
        [1] => application/xhtml+xml
        [2] => image/webp
        [3] => application/xml;q=0.9
        [4] => */*;q=0.8
    )

### `HTTPHeader::Accept_Encoding($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => gzip
        [1] => deflate
    )


### `HTTPHeader::Accept_Language($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => en-GB
        [1] => en;q=0.7
        [2] => en-US;q=0.3
    )

### `HTTPHeader::Accept_Patch($string)`

Returns an array of media types.

Example:

    Array
    (
        [0] => application/example
        [1] => text/example;charset=utf-8
    )

### `HTTPHeader::Accept_Post($string)`

Returns an array of media types.

Example:

    Array
    (
        [0] => image/webp
        [1] => text/example;charset=utf-8
    )

### `HTTPHeader::Accept_Ranges($string = null)`

Returns a range unit.

### `HTTPHeader::Access_Control_Allow_Credentials($string)`

Returns `true` if the value is "true", or `null` otherwise.

### `HTTPHeader::Access_Control_Allow_Headers($string)`

Returns an array of headers.

Example:

    Array
    (
        [0] => Accept
        [1] => Upgrade-Insecure-Requests
    )

### `HTTPHeader::Access_Control_Allow_Methods($string)`

Returns an array of method names, or `null` if the value is invalid.

Example:

    Array
    (
        [0] => POST
        [1] => GET
        [2] => OPTIONS
    )

### `HTTPHeader::Access_Control_Allow_Origin($string)`

Returns the string "\*", or the result of `parse_url()` on the supplied value, or `null` if the value is either "null" or invalid.

### `HTTPHeader::Access_Control_Expose_Headers($string)`

Returns an array of headers.

Example:

    Array
    (
        [0] => Content-Encoding
    )


### `HTTPHeader::Access_Control_Max_Age($string)`

Returns a non-negative integer representing the number of seconds, or `null` if the value is invalid.

### `HTTPHeader::Access_Control_Request_Headers($string = null)`

Returns an array of headers.

Example:

    Array
    (
        [0] => Content-Type
    )

### `HTTPHeader::Access_Control_Request_Method($string)`

Returns the method name, or `null` if the value is invalid.

### `HTTPHeader::Age($string)`

Returns a non-negative integer representing a time delta in seconds, or `null` if the value is invalid.

### `HTTPHeader::Allow($string)`

Returns an array of method names, or `null` if the value is invalid.

Example:

    Array
    (
        [0] => GET
        [1] => POST
        [2] => HEAD
    )

### `HTTPHeader::Authorization($string = null)`

Returns an array containing the authorization type and an array of comma-separated parameters.

Example:

    Array
    (
        [0] => Basic
        [1] => Array
            (
                [0] => YWxhZGRpbjpvcGVuc2VzYW1l
            )
    )

### `HTTPHeader::Cache_Control($string = null)`

Returns an array of directives.

Example:

    Array
    (
        [0] => only-if-cached
    )

### `HTTPHeader::Clear_Site_Data($string)`

Returns an array of directives.

Example:

    Array
    (
        [0] => "storage"
        [1] => "cookies"
    )

### `HTTPHeader::Connection($string = null)`

Returns an array of directives.

Example:

    Array
    (
        [0] => keep-alive
    )

### `HTTPHeader::Content_Length($string = null)`

Returns the content length in decimal number of octets, or `null` if the value is invalid.

### `HTTPHeader::Content_Type($string = null)`

Returns an associative array containing the content type, charset and boundary if supplied.

Example:

    Array
    (
        [type] => multipart/form-data
        [boundary] => something
    )

### `HTTPHeader::Cookie($string = null)`

Returns an associative array of cookie names and values.

Example:

    Array
    (
        [Session] => 0ae5ab57925bcbee58917d552acb4cd4
    )

### `HTTPHeader::Date($string = null)`

Returns a DateTimeImmutable object, or `null` if the value is invalid.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Downlink($string = null)`

Returns a non-negative integer representing the downlink rate in Mbps, or `null` if the value is invalid.

### `HTTPHeader::DNT($string = null)`

Returns 0, 1, or `null` if the value is indeterminate.

### `HTTPHeader::ETag($string)`

Returns an ETag value, or `null` if the value is invalid.

### `HTTPHeader::Expect($string = null)`

Returns 100 if the value is "100-continue", or `null` otherwise.

### `HTTPHeader::Expires($string)`

Returns a DateTimeImmutable object, or `null` if the value is invalid.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Forwarded($string = null)`

Returns an array of fields, each containing an associative array of directives.

Example:

    Array
    (
        [0] => Array
            (
                [for] => 192.0.2.60
                [proto] => http
                [by] => 203.0.113.43
            )
        [1] => Array
            (
                [for] => 198.51.100.17
            )
    )

### `HTTPHeader::From($string = null)`

Returns a string containing the supplied email address, or `null` if the value is invalid.

### `HTTPHeader::Host($string = null)`

Returns an associative array containing the host, and port if supplied, or `null` if the value is invalid.

Example:

    Array
    (
        [host] => example.com
        [port] => 80
    )

### `HTTPHeader::If_Match($string = null)`

Returns an array of ETag values, or `null` if the value is invalid.

Example:

    Array
    (
        [0] => W/"67ab43"
        [1] => "54ed21"
        [2] => "7892dd"
    )

### `HTTPHeader::If_Modified_Since($string = null)`

Returns a DateTimeImmutable object, or `null` if the value is invalid.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::If_None_Match($string = null)`

Returns an array of ETag values, or `null` if the value is invalid.

Example:

    Array
    (
        [0] => W/"67ab43"
        [1] => "54ed21"
        [2] => "7892dd"
    )

### `HTTPHeader::If_Range($string = null)`

Returns a DateTimeImmutable object, or an array of ETag values, or `null` if the value is invalid.

### `HTTPHeader::If_Unmodified_Since($string = null)`

Returns a DateTimeImmutable object, or `null` if the value is invalid.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Keep_Alive($string = null)`

Returns an associative array of parameters.

Example:

    Array
    (
        [timeout] => 5
        [max] => 1000
    )

### `HTTPHeader::Last_Modified($string)`

Returns a DateTimeImmutable object, or `null` if the value is invalid.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Origin($string = null)`

Returns the result of `parse_url()` on the supplied value, or `null` if the value is invalid.

Example:

    Array
    (
        [scheme] => https
        [host] => example.com
    )

### `HTTPHeader::Proxy_Authorization($string = null)`

Returns an array containing the authorization type and an array of comma-separated parameters.

Example:

    Array
    (
        [0] => Basic
        [1] => Array
            (
                [0] => YWxhZGRpbjpvcGVuc2VzYW1l
            )
    )

### `HTTPHeader::Range($string = null)`

Returns an associative array containing the unit and ranges, or `null` if the value is invalid.

Example:

    Array
    (
        [unit] => bytes
        [ranges] => Array
            (
                [0] => 200-1000
                [1] => 2000-6576
                [2] => 19000-
            )
    )

### `HTTPHeader::Referer($string = null)`

Returns the result of `parse_url()` on the supplied value, or `null` if the value is invalid.

Example:

    Array
    (
        [scheme] => https
        [host] => example.com
        [path] => /foo/
    )

### `HTTPHeader::RTT($string = null)`

Returns a non-negative integer representing the approximate round trip time in milliseconds, or `null` if the value is invalid.

### `HTTPHeader::Save_Data($string = null)`

Returns 0 for falsey values, 1 for truthy values, or `null` if the value is indeterminate.

### `HTTPHeader::Sec_Fetch_Dest($string = null)`

Returns a directive, or `null` if the value is invalid.

### `HTTPHeader::Sec_Fetch_Mode($string = null)`

Returns a directive, or `null` if the value is invalid.

### `HTTPHeader::Sec_Fetch_Site($string = null)`

Returns a directive, or `null` if the value is invalid.

### `HTTPHeader::Sec_Fetch_User($string = null)`

Returns `true` if the value is "?1", or `null` otherwise.

### `HTTPHeader::Sec_GPC($string = null)`

Returns `true` if the value is "1", or `null` otherwise.

### `HTTPHeader::TE($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => trailers
        [1] => gzip
        [2] => deflate;q=0.5
    ) 

### `HTTPHeader::Upgrade($string = null)`

Returns an array of protocols in order of preference.

Example:

    Array
    (
        [0] => HTTP/2
        [1] => HTTP/1.1
    ) 

### `HTTPHeader::Upgrade_Insecure_Requests($string = null)`

Returns 0, 1, or `null` if the value is indeterminate.

### `HTTPHeader::User_Agent($string = null)`

Returns an associative array containing the product, version, and comment, or `null` if the value is invalid.

Example:

    Array
    (
        [product] => Mozilla
        [version] => 5.0
        [comment] => (Windows NT 10.0; Win64; x64; rv:84.0) Gecko/20100101 Firefox/84.0
    )

### `HTTPHeader::Via($string = null)`

Returns an array of proxy identifiers.

Example:

    Array
    (
        [0] => 1.0 foo
        [1] => 1.1 bar.example.com
    )

### `HTTPHeader::Want_Digest($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => sha-256;q=1
        [1] => SHA-512;q=0.3
        [2] => md5;q=0
    )

### `HTTPHeader::WWW_Authenticate($string)`

Returns an array containing the authorization type and an array of comma-separated parameters.

Example:

    Array
    (
        [0] => auth-scheme
        [1] => Array
            (
                [0] => realm=realm
                [1] => token68
                [2] => auth-param1=auth-param1-token
                [3] => auth-param2=auth-param2-token
            )
    )
