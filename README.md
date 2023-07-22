## What is this?

HTTPHeader is a PHP class for inspecting HTTP headers.

## Requirements

* PHP 8.0+

## Usage

Methods for inspecting response headers must be supplied with a string containing a single header (with or without field name) or a complete HTTP response. Methods for inspecting request headers can optionally be supplied with a string containing a single header (with or without field name) or a complete HTTP request; if not supplied with a string, these methods will attempt to read the value from the `$_SERVER` superglobal.

Methods will return `false` if the header is not present or cannot be parsed, and `null` if the field value is malformed.

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

Returns an array of method names, or `null` if the field value is malformed.

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

Returns a non-negative integer representing the number of seconds, or `null` if the field value is malformed.

### `HTTPHeader::Access_Control_Request_Headers($string = null)`

Returns an array of headers.

Example:

    Array
    (
        [0] => Content-Type
    )

### `HTTPHeader::Access_Control_Request_Method($string)`

Returns the method name, or `null` if the field value is malformed.

### `HTTPHeader::Age($string)`

Returns a non-negative integer representing a time delta in seconds, or `null` if the field value is malformed.

### `HTTPHeader::Allow($string)`

Returns an array of method names, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => GET
        [1] => POST
        [2] => HEAD
    )

### `HTTPHeader::Alt_Svc($string)`

Returns an array of arrays containing the comma-separated parameters for each alternative service.

Example:

    Array
    (
        [0] => Array
            (
                [0] => h3-25=":443"
                [1] => ma=3600
            )
        [1] => Array
            (
                [0] => h2=":443"
                [1] => ma=3600
            )
    )

### `HTTPHeader::Authorization($string = null)`

Returns an array containing the authorization type and parameters, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Basic
        [1] => YWxhZGRpbjpvcGVuc2VzYW1l
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
        [0] => storage
        [1] => cookies
    )

### `HTTPHeader::Connection($string = null)`

Returns an array of directives.

Example:

    Array
    (
        [0] => keep-alive
    )

### `HTTPHeader::Content_Disposition($string = null)`

Returns an associative array containing the content disposition, field name and filename (if supplied), or `null` if the field value is malformed.

Example:

    Array
    (
        [disposition] => attachment
        [filename] => filename.jpg
    )

### `HTTPHeader::Content_Encoding($string)`

Returns an array of encoding formats in the order in which they were applied, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => deflate
        [1] => gzip
    )

### `HTTPHeader::Content_Language($string = null)`

Returns an array of language tags.

Example:

    Array
    (
        [0] => en-GB
        [1] => en
    )

### `HTTPHeader::Content_Location($string)`

Returns a string.

### `HTTPHeader::Content_Length($string = null)`

Returns the content length in decimal number of octets, or `null` if the field value is malformed.

### `HTTPHeader::Content_Range($string)`

Returns an associative array containing the unit range, and size, or `null` if the field value is malformed.

Example:

    Array
    (
        [unit] => bytes
        [range] => 200-1000
        [size] => 67589
    )

### `HTTPHeader::Content_Security_Policy($string)`

Returns an array of arrays containing the policy directive and values.

Example:

    Array
    (
        [0] => Array
            (
                [0] => default-src
                [1] => Array
                    (
                        [0] => self
                        [1] => nonce-DhcnhD3khTMePgXwdayK9BsMqXjhguVV
                    )
            )
        [1] => Array
            (
                [0] => form-action
                [1] => Array
                    (
                        [0] => self
                    )
            )

### `HTTPHeader::Content_Security_Policy_Report_Only($string)`

See above.

### `HTTPHeader::Content_Type($string = null)`

Returns an associative array containing the content type, charset and boundary (if supplied), or `null` if the field value is malformed.

Example:

    Array
    (
        [type] => multipart/form-data
        [boundary] => something
    )

### `HTTPHeader::Cookie($string = null)`

Returns an array of arrays containing the cookie names and value, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Array
            (
                [0] => Session
                [1] => 0ae5ab57925bcbee58917d552acb4cd4
            )
    )

### `HTTPHeader::Cross_Origin_Embedder_Policy($string)`

Returns a policy directive, or `null` if the field value is malformed.

### `HTTPHeader::Cross_Origin_Opener_Policy($string)`

Returns a policy directive, or `null` if the field value is malformed.

### `HTTPHeader::Cross_Origin_Resource_Policy($string)`

Returns a policy directive, or `null` if the field value is malformed.

### `HTTPHeader::Date($string = null)`

Returns a DateTimeImmutable object, or `null` if the field value is malformed.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Device_Memory($string = null)`

Returns a non-negative float representing the device memory in GiB, or `null` if the field value is malformed.

### `HTTPHeader::Downlink($string = null)`

Returns a non-negative float representing the downlink rate in Mbps, or `null` if the field value is malformed.

### `HTTPHeader::DNT($string = null)`

Returns 0, 1, or `null` if the value is indeterminate.

### `HTTPHeader::ETag($string)`

Returns an ETag value, or `null` if the field value is malformed.

### `HTTPHeader::Expect($string = null)`

Returns 100 if the value is "100-continue", or `null` otherwise.

### `HTTPHeader::Expires($string)`

Returns a DateTimeImmutable object, or `null` if the field value is malformed.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Forwarded($string = null)`

Returns an array of fields, each containing an associative array of directives, or `null` if the field value is malformed.

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

Returns a string containing the supplied email address, or `null` if the field value is malformed.

### `HTTPHeader::Host($string = null)`

Returns an associative array containing the host, and port if supplied, or `null` if the field value is malformed.

Example:

    Array
    (
        [host] => example.com
        [port] => 80
    )

### `HTTPHeader::If_Match($string = null)`

Returns an array of ETag values, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => W/"67ab43"
        [1] => "54ed21"
        [2] => "7892dd"
    )

### `HTTPHeader::If_Modified_Since($string = null)`

Returns a DateTimeImmutable object, or `null` if the field value is malformed.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::If_None_Match($string = null)`

Returns an array of ETag values, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => W/"67ab43"
        [1] => "54ed21"
        [2] => "7892dd"
    )

### `HTTPHeader::If_Range($string = null)`

Returns a DateTimeImmutable object, or an array of ETag values, or `null` if the field value is malformed.

### `HTTPHeader::If_Unmodified_Since($string = null)`

Returns a DateTimeImmutable object, or `null` if the field value is malformed.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Keep_Alive($string = null)`

Returns an associative array of parameters, or `null` if the field value is malformed.

Example:

    Array
    (
        [timeout] => 5
        [max] => 1000
    )

### `HTTPHeader::Last_Modified($string)`

Returns a DateTimeImmutable object, or `null` if the field value is malformed.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Link($string)`

Returns an array of arrays containing the link URI and an array of parameters, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Array
            (
                [0] => https://one.example.com
                [1] => Array
                    (
                        [0] => Array
                            (
                                [0] => rel
                                [1] => preconnect
                            )
                    )
            )
        [1] => Array
            (
                [0] => https://two.example.com
                [1] => Array
                    (
                        [0] => Array
                            (
                                [0] => rel
                                [1] => preconnect
                            )
                        [1] => Array
                            (
                                [0] => foo
                                [1] => bar
                            )
                    )
            )
    )

### `HTTPHeader::Location($string)`

Returns a string.

### `HTTPHeader::Max_Forwards($string = null)`

Returns a non-negative integer, or `null` if the field value is malformed.

### `HTTPHeader::Origin($string = null)`

Returns the result of `parse_url()` on the supplied value, or `null` if the field value is malformed.

Example:

    Array
    (
        [scheme] => https
        [host] => example.com
    )

### `HTTPHeader::Permissions_Policy($string)`

Returns an array of arrays containing the policy directive and values, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Array
            (
                [0] => picture-in-picture
                [1] => Array
                    (
                    )
            )
        [1] => Array
            (
                [0] => geolocation
                [1] => Array
                    (
                        [0] => self
                        [1] => https://example.com
                    )
            )
        [2] => Array
            (
                [0] => camera
                [1] => Array
                    (
                        [0] => *
                    )
            )
    )

### `HTTPHeader::Proxy_Authenticate($string)`

Returns an array of arrays containing the authentication type and parameters, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Array
            (
                [0] => Basic
                [1] => realm="foo"
            )
        [1] => Array
            (
                [0] => Other
                [1] => realm="bar",
            )
    )

### `HTTPHeader::Proxy_Authorization($string = null)`

Returns an array containing the authorization type and parameters, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Basic
        [1] => YWxhZGRpbjpvcGVuc2VzYW1l
    )

### `HTTPHeader::Range($string = null)`

Returns an associative array containing the unit and ranges, or `null` if the field value is malformed.

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

Returns the result of `parse_url()` on the supplied value, or `null` if the field value is malformed.

Example:

    Array
    (
        [scheme] => https
        [host] => example.com
        [path] => /foo/
    )

### `HTTPHeader::Referrer_Policy($string)`

Returns a directive, or `null` if the field value is malformed.

### `HTTPHeader::Retry_After($string = null)`

Returns a DateTimeImmutable object, or a non-negative integer representing the delay in seconds, or `null` if the field value is malformed.

### `HTTPHeader::RTT($string = null)`

Returns a non-negative integer representing the approximate round trip time in milliseconds, or `null` if the field value is malformed.

### `HTTPHeader::Save_Data($string = null)`

Returns 0 for falsey values, 1 for truthy values, or `null` if the value is indeterminate.

### `HTTPHeader::Sec_Fetch_Dest($string = null)`

Returns a directive, or `null` if the field value is malformed.

### `HTTPHeader::Sec_Fetch_Mode($string = null)`

Returns a directive, or `null` if the field value is malformed.

### `HTTPHeader::Sec_Fetch_Site($string = null)`

Returns a directive, or `null` if the field value is malformed.

### `HTTPHeader::Sec_Fetch_User($string = null)`

Returns `true` if the value is "?1", or `null` otherwise.

### `HTTPHeader::Sec_GPC($string = null)`

Returns `true` if the value is "1", or `null` otherwise.

### `HTTPHeader::Sec_Purpose($string = null)`

Returns a directive, or `null` if the field value is malformed.

### `HTTPHeader::Server($string)`

Returns a string.

### `HTTPHeader::Server_Timing($string)`

Returns an array of associative arrays containing the metric name, description and duration (if supplied), or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Array
            (
                [name] => db
                [dur] => 53
            )
        [1] => Array
            (
                [name] => cache
                [desc] => Cache Read
                [dur] => 23.2
            )
    )

### `HTTPHeader::Service_Worker_Navigation_Preload($string)`

Returns a string.

### `HTTPHeader::Set_Cookie($string)`

Returns an array of arrays containing the cookie name and value, and an associative array of parameter values, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Array
            (
                [0] => Session
                [1] => 0ae5ab57925bcbee58917d552acb4cd4
            )
        [1] => Array
            (
                [Path] => /
                [Domain] => example.com
                [SameSite] => 
                [Expires] => DateTimeImmutable Object
                    (
                        [date] => 2015-10-21 07:28:00.000000
                        [timezone_type] => 2
                        [timezone] => GMT
                    )
                [Max-Age] => 20
                [HttpOnly] => 1
                [Secure] => 1
                [Partitioned] => 
            )
    )

### `HTTPHeader::SourceMap($string)`

Returns a string.

### `HTTPHeader::Strict_Transport_Security($string)`

Returns an associative array of parameter values, or `null` if the field value is malformed.

Example:

    Array
    (
        [max-age] => 63072000
        [includeSubDomains] => 1
        [preload] => 1
    )

### `HTTPHeader::TE($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => trailers
        [1] => gzip
        [2] => deflate;q=0.5
    ) 

### `HTTPHeader::Timing_Allow_Origin($string)`

Returns the string "\*", or an array containing the results of `parse_url()` on each of the supplied values.

Example:

    Array
    (
        [0] => Array
            (
                [scheme] => https
                [host] => mozilla.org
            )
        [1] => Array
            (
                [scheme] => http
                [host] => example.com
            )
    )

### `HTTPHeader::Trailer($string)`

Returns an array of field names.

Example:

    Array
    (
        [0] => Expires
        [1] => Date
    )

### `HTTPHeader::Transfer_Encoding($string)`

Returns an array of encoding formats in the order in which they were applied, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => gzip
        [1] => chunked
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

Returns an associative array containing the product, version, and comment, or `null` if the field value is malformed.

Example:

    Array
    (
        [product] => Mozilla
        [version] => 5.0
        [comment] => (Windows NT 10.0; Win64; x64; rv:84.0) Gecko/20100101 Firefox/84.0
    )

### `HTTPHeader::Vary($string)`

Returns the string "\*", or an array of field names.

Example:

    Array
    (
        [0] => Cookie
        [1] => Save-Data
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

Returns an array of arrays containing the authentication type and parameters, or `null` if the field value is malformed.

Example:

    Array
    (
        [0] => Array
            (
                [0] => scheme1
                [3] => realm="foo"
                [4] => param1=token1
                [5] => param2=token2
            )
        [1] => Array
            (
                [0] => scheme2
                [1] => token68
                [2] => realm="bar"
            )
        [2] => Array
            (
                [0] => scheme3
            )
    )
