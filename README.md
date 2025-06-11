## What is this?

HTTPHeader is a PHP class for inspecting HTTP headers.

## Requirements

* PHP 8.0+

## Usage

Methods for inspecting response headers must be supplied with a string containing one or more headers, or a complete HTTP response.

Examples:

    use xenocrat\HTTPHeader;
    $result = HTTPHeader::Server("Server: Apache");
    $result = HTTPHeader::Server("Content-Type: text/plain\r\nServer: Apache");
    $result = HTTPHeader::Server("HTTP/1.1 200 OK\r\nServer: Apache\r\n\r\nHello, world!");

Methods for inspecting request headers can optionally be supplied with a string containing one or more headers, or a complete HTTP request; if not supplied with a string, these methods will attempt to read the value from the `$_SERVER` superglobal.

Examples:

    use xenocrat\HTTPHeader;
    $result = HTTPHeader::Accept();
    $result = HTTPHeader::Accept("Accept: text/html, application/xhtml+xml");
    $result = HTTPHeader::Accept("Accept: text/html\r\nAccept-Encoding: gzip");

All fields can be extracted from a string containing multiple headers, or from a complete HTTP request or response, using the `extract()` method. The return value is an associative array of arrays containing one entry for each occurence of a field:

Example:

    $result = HTTPHeader::extract($request_or_response);
    print_r($result);
    
    Array
    (
        [CONTENT_TYPE] => Array
            (
                [0] => Array
                    (
                        [type] => text/plain
                    )
            )
        [SERVER] => Array
            (
                [0] => Array
                    (
                        [0] => Array
                            (
                                [product] => Apache
                            )
                    )
            )
    )

## Methods

Methods will return `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### `Accept($string = null)`

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

#### `Accept_CH($string)`

Returns an array of client hint headers.

#### `Accept_Charset($string = null)`

Returns an array sorted by "q" value.

#### `Accept_Encoding($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => gzip
        [1] => deflate
    )

#### `Accept_Language($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => en-GB
        [1] => en;q=0.7
        [2] => en-US;q=0.3
    )

#### `Accept_Patch($string)`

Returns an array of media types.

Example:

    Array
    (
        [0] => application/example
        [1] => text/example;charset=utf-8
    )

#### `Accept_Post($string)`

Returns an array of media types.

Example:

    Array
    (
        [0] => image/webp
        [1] => text/example;charset=utf-8
    )

#### `Accept_Ranges($string)`

Returns a range unit.

#### `Access_Control_Allow_Credentials($string)`

Returns `true` if the value is "true", or `null` otherwise.

#### `Access_Control_Allow_Headers($string)`

Returns an array of headers.

Example:

    Array
    (
        [0] => Accept
        [1] => Upgrade-Insecure-Requests
    )

#### `Access_Control_Allow_Methods($string)`

Returns an array of method names.

Example:

    Array
    (
        [0] => POST
        [1] => GET
        [2] => OPTIONS
    )

#### `Access_Control_Allow_Origin($string)`

Returns the string `"*"`, the string `"null"` if the value is "null", or the result of `parse_url()` on the supplied value.

#### `Access_Control_Expose_Headers($string)`

Returns an array of headers.

Example:

    Array
    (
        [0] => Content-Encoding
    )

#### `Access_Control_Max_Age($string)`

Returns a non-negative integer representing the number of seconds.

#### `Access_Control_Request_Headers($string = null)`

Returns an array of headers.

Example:

    Array
    (
        [0] => Content-Type
    )

#### `Access_Control_Request_Method($string = null)`

Returns the method name.

#### `Age($string)`

Returns a non-negative integer representing a time delta in seconds.

#### `Allow($string)`

Returns an array of method names.

Example:

    Array
    (
        [0] => GET
        [1] => POST
        [2] => HEAD
    )

#### `Alt_Svc($string)`

Returns the string `"clear"`, or an array of associative arrays containing the parameters for each alternative service.

Example:

    Array
    (
        [0] => Array
            (
                [protocol] => h3-25
                [host] => 
                [port] => 443
                [ma] => 3600
                [persist] => <true|false>
            )
        [1] => Array
            (
                [protocol] => h2
                [host] => example.com
                [port] => 443
                [ma] => 3600
                [persist] => <true|false>
            )
    )

#### `Alt_Used($string = null)`

Returns an associative array containing the host, and port if supplied.

Example:

    Array
    (
        [host] => example.com
        [port] => 80
    )

#### `Authorization($string = null)`

Returns an array containing the authorization type and parameters.

Example:

    Array
    (
        [0] => Basic
        [1] => YWxhZGRpbjpvcGVuc2VzYW1l
    )

#### `Cache_Control($string = null)`

Returns an array of directives.

Example:

    Array
    (
        [0] => only-if-cached
    )

#### `Clear_Site_Data($string)`

Returns an array of directives.

Example:

    Array
    (
        [0] => storage
        [1] => cookies
    )

#### `Connection($string = null)`

Returns an array of directives.

Example:

    Array
    (
        [0] => keep-alive
    )

#### `Content_Disposition($string)`

Returns an associative array containing the content disposition, field name and filename (if supplied).

Example:

    Array
    (
        [disposition] => attachment
        [filename] => filename.jpg
    )

#### `Content_Encoding($string)`

Returns an array of encoding formats in the order in which they were applied.

Example:

    Array
    (
        [0] => deflate
        [1] => gzip
    )

#### `Content_Language($string = null)`

Returns an array of language tags.

Example:

    Array
    (
        [0] => en-GB
        [1] => en
    )

#### `Content_Length($string = null)`

Returns the content length in decimal number of octets.

#### `Content_Location($string)`

Returns a string.

#### `Content_Range($string)`

Returns an associative array containing the unit, range, and size.

Example:

    Array
    (
        [unit] => bytes
        [range] => 200-1000
        [size] => 67589
    )

#### `Content_Security_Policy($string)`

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

#### `Content_Security_Policy_Report_Only($string)`

See above.

#### `Content_Type($string = null)`

Returns an associative array containing the content type, charset and boundary (if supplied).

Example:

    Array
    (
        [type] => multipart/form-data
        [boundary] => something
    )

#### `Cookie($string = null)`

Returns an array of arrays containing the cookie names and value.

Example:

    Array
    (
        [0] => Array
            (
                [0] => Session
                [1] => 0ae5ab57925bcbee58917d552acb4cd4
            )
    )

#### `Cross_Origin_Embedder_Policy($string)`

Returns a policy directive.

#### `Cross_Origin_Opener_Policy($string)`

Returns a policy directive.

#### `Cross_Origin_Resource_Policy($string)`

Returns a policy directive.

#### `Date($string = null)`

Returns a DateTimeImmutable object.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

#### `Device_Memory($string = null)`

Returns a non-negative float representing the device memory in GiB.

#### `Digest($string)`

Returns an array of arrays containing the digest algorithms and values.

Example:

    Array
    (
        [0] => Array
            (
                [0] => sha-256
                [1] => X48E9qOokqqrvdts8nOJRJN3OWDUoyWxBf7kbu9DBPE=
            )
        [1] => Array
            (
                [0] => unixsum
                [1] => 30637
            )
    )

#### `Downlink($string = null)`

Returns a non-negative float representing the downlink rate in Mbps.

#### `DNT($string = null)`

Returns the integer `0`, `1`, or `null` if the value is indeterminate.

#### `ECT($string = null)`

Returns a string identifying the effective connection type, or `null` if the value is indeterminate.

#### `ETag($string)`

Returns an ETag value.

#### `Expect($string = null)`

Returns the integer `100` if the value is "100-continue", or `null` otherwise.

#### `Expires($string)`

Returns a DateTimeImmutable object.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

#### `Forwarded($string = null)`

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

#### `From($string = null)`

Returns a string containing the supplied email address.

#### `Host($string = null)`

Returns an associative array containing the host, and port if supplied.

Example:

    Array
    (
        [host] => example.com
        [port] => 80
    )

#### `If_Match($string = null)`

Returns an array of ETag values.

Example:

    Array
    (
        [0] => W/"67ab43"
        [1] => "54ed21"
        [2] => "7892dd"
    )

#### `If_Modified_Since($string = null)`

Returns a DateTimeImmutable object.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

#### `If_None_Match($string = null)`

Returns an array of ETag values.

Example:

    Array
    (
        [0] => W/"67ab43"
        [1] => "54ed21"
        [2] => "7892dd"
    )

#### `If_Range($string = null)`

Returns a DateTimeImmutable object, or an array of ETag values.

#### `If_Unmodified_Since($string = null)`

Returns a DateTimeImmutable object.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

#### `Keep_Alive($string = null)`

Returns an associative array of parameters.

Example:

    Array
    (
        [timeout] => 5
        [max] => 1000
    )

#### `Last_Modified($string)`

Returns a DateTimeImmutable object.

Example:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

#### `Link($string)`

Returns an array of arrays containing the link URI and an array of parameters.

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

#### `Location($string)`

Returns a string.

#### `Max_Forwards($string = null)`

Returns a non-negative integer.

#### `Origin($string = null)`

Returns the result of `parse_url()` on the supplied value.

Example:

    Array
    (
        [scheme] => https
        [host] => example.com
    )

#### `Permissions_Policy($string)`

Returns an array of arrays containing the policy directive and values.

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

#### `Pragma($string = null)`

Returns an array of directives.

#### `Proxy_Authenticate($string)`

Returns an array of arrays containing the authentication type and parameters.

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
                [1] => realm="bar"
            )
    )

#### `Proxy_Authorization($string = null)`

Returns an array containing the authorization type and parameters.

Example:

    Array
    (
        [0] => Basic
        [1] => YWxhZGRpbjpvcGVuc2VzYW1l
    )

#### `Range($string = null)`

Returns an associative array containing the unit and ranges.

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

#### `Referer($string = null)`

Returns the result of `parse_url()` on the supplied value.

Example:

    Array
    (
        [scheme] => https
        [host] => example.com
        [path] => /foo/
    )

#### `Referrer_Policy($string)`

Returns a directive.

#### `Retry_After($string)`

Returns a DateTimeImmutable object, or a non-negative integer representing the delay in seconds.

#### `RTT($string = null)`

Returns a non-negative integer representing the approximate round trip time in milliseconds.

#### `Save_Data($string = null)`

Returns the integer `0` for falsey values, `1` for truthy values, or `null` if the value is indeterminate.

#### `Sec_Fetch_Dest($string = null)`

Returns a directive.

#### `Sec_Fetch_Mode($string = null)`

Returns a directive.

#### `Sec_Fetch_Site($string = null)`

Returns a directive.

#### `Sec_Fetch_User($string = null)`

Returns `true` if the value is "?1", or `null` otherwise.

#### `Sec_GPC($string = null)`

Returns `true` if the value is "1", or `null` otherwise.

#### `Sec_Purpose($string = null)`

Returns a directive.

#### `Server($string)`

Returns an array of associative arrays containing the product, version and comment (if supplied).

Example:

    Array
    (
        [0] => Array
            (
                [product] => Apache
                [version] => 2.4.1
                [comment] => Unix
            )
    )

#### `Server_Timing($string)`

Returns an array of associative arrays containing the metric name, description and duration (if supplied).

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

#### `Service_Worker_Navigation_Preload($string = null)`

Returns a string.

#### `Set_Cookie($string)`

Returns an array of arrays containing the cookie name and value, and an associative array of parameter values.

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
                [HttpOnly] => <true|false>
                [Secure] => <true|false>
                [Partitioned] => <true|false>
            )
    )

#### `Set_Login($string)`

Returns the string `"logged-in"`, the string `"logged-out"`, or `null` if the value is indeterminate.

#### `SourceMap($string)`

Returns a string.

#### `Strict_Transport_Security($string)`

Returns an associative array of parameter values.

Example:

    Array
    (
        [max-age] => 63072000
        [includeSubDomains] => <true|false>
        [preload] => <true|false>
    )

#### `TE($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => trailers
        [1] => gzip
        [2] => deflate;q=0.5
    ) 

#### `Timing_Allow_Origin($string)`

Returns the string `"*"`, or an array containing the results of `parse_url()` on each of the supplied values.

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

#### `Trailer($string)`

Returns an array of field names.

Example:

    Array
    (
        [0] => Expires
        [1] => Date
    )

#### `Transfer_Encoding($string)`

Returns an array of encoding formats in the order in which they were applied.

Example:

    Array
    (
        [0] => gzip
        [1] => chunked
    )

#### `Upgrade($string = null)`

Returns an array of protocols in order of preference.

Example:

    Array
    (
        [0] => HTTP/2
        [1] => HTTP/1.1
    ) 

#### `Upgrade_Insecure_Requests($string = null)`

Returns the integer `0`, `1`, or `null` if the value is indeterminate.

#### `User_Agent($string = null)`

Returns an array of associative arrays containing the product, version and comment (if supplied).

Example:

    Array
    (
        [0] => Array
            (
                [product] => Mozilla
                [version] => 5.0
                [comment] => Windows NT 10.0; Win64; x64; rv:109.0
            )
        [1] => Array
            (
                [product] => Gecko
                [version] => 20100101
            )
        [2] => Array
            (
                [product] => Firefox
                [version] => 115.0
            )
    )

#### `Vary($string)`

Returns the string `"*"`, or an array of field names.

Example:

    Array
    (
        [0] => Cookie
        [1] => Save-Data
    )

#### `Via($string = null)`

Returns an array of associative arrays containing the details supplied by each proxy.

Example:

    Array
    (
        [0] => Array
            (
                [protocol] => HTTP
                [version] => 1.0
                [pseudonym] => foo
            )
        [1] => Array
            (
                [version] => 1.1
                [pseudonym] => bar
            )
        [2] => Array
            (
                [version] => 1.1
                [pseudonym] => example.com
                [port] => 80
                [comment] => this is a comment
            )
    )

#### `Want_Digest($string = null)`

Returns an array sorted by "q" value.

Example:

    Array
    (
        [0] => sha-256;q=1
        [1] => SHA-512;q=0.3
        [2] => md5;q=0
    )

#### `WWW_Authenticate($string)`

Returns an array of arrays containing the authentication type and parameters.

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

#### `X_Content_Type_Options($string)`

Returns the string `"nosniff"`, or `null` if the value is indeterminate.

#### `X_Frame_Options($string)`

Returns a directive.
