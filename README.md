## What is this?

HTTPHeader is a PHP class for inspecting HTTP request headers.

## Requirements

* PHP 5.4+

## Usage

Methods will check for the corresponding header in the `$_SERVER` superglobal by default, but will also accept a string containing a complete header as an argument.

### `HTTPHeader::Accept()`

Returns an array sorted by "q" value:

    Array
    (
        [0] => text/html
        [1] => application/xhtml+xml
        [2] => image/webp
        [3] => application/xml;q=0.9
        [4] => */*;q=0.8
    )

### `HTTPHeader::Accept_Encoding()`

Returns an array sorted by "q" value:

    Array
    (
        [0] => gzip
        [1] => deflate
    )

### `HTTPHeader::Accept_Language()`

Returns an array sorted by "q" value:

    Array
    (
        [0] => en-GB
        [1] => en;q=0.7
        [2] => en-US;q=0.3
    )

### `HTTPHeader::Authorization()`

Returns an array containing the authorization type and credentials:

    Array
    (
        [0] => Basic
        [1] => YWxhZGRpbjpvcGVuc2VzYW1l
    )

### `HTTPHeader::Cache_Control()`

Returns an array of directives:

    Array
    (
        [0] => only-if-cached
    )

### `HTTPHeader::Connection()`

Returns an array of directives:

    Array
    (
        [0] => keep-alive
    )

### `HTTPHeader::Content_Type()`

Returns an array containing the content type and additional directives:

    Array
    (
        [0] => multipart/form-data
        [1] => boundary=something
    )

### `HTTPHeader::Cookie()`

Returns an associative array of cookie names and values:

    Array
    (
        [Session] => 0ae5ab57925bcbee58917d552acb4cd4
    )

### `HTTPHeader::Date()`

Returns a DateTimeImmutable object:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::DNT()`

Returns 0, 1, or `null` if the value is indeterminate.

### `HTTPHeader::Forwarded()`

Returns an array of fields, each containing one an associative array of directives:

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

### `HTTPHeader::From()`

Returns a string containing the supplied email address.

### `HTTPHeader::Host()`

Returns an associative array containing the host value, and port value if supplied:

    Array
    (
        [host] => example.com
        [port] => 80
    )

### `HTTPHeader::If_Match()`

Returns an array of ETag values:

    Array
    (
        [0] => "67ab43"
        [1] => "54ed21"
        [2] => "7892dd"
    )

### `HTTPHeader::If_Modified_Since()`

Returns a DateTimeImmutable object:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::If_None_Match()`

Returns an array of ETag values:

    Array
    (
        [0] => W/"67ab43"
        [1] => "54ed21"
        [2] => "7892dd"
    )

### `HTTPHeader::If_Range()`

Returns a DateTimeImmutable object, or an array of ETag values.

### `HTTPHeader::If_Unmodified_Since()`

Returns a DateTimeImmutable object:

    DateTimeImmutable Object
    (
        [date] => 2015-10-21 07:28:00.000000
        [timezone_type] => 2
        [timezone] => GMT
    )

### `HTTPHeader::Keep_Alive()`

Returns an associative array of parameters:

    Array
    (
        [timeout] => 5
        [max] => 1000
    )

### `HTTPHeader::Origin()`

Returns the result of `parse_url()` on the supplied value:

    Array
    (
        [scheme] => https
        [host] => example.com
    )

### `HTTPHeader::Proxy_Authorization()`

Returns an array containing the authorization type and credentials:

    Array
    (
        [0] => Basic
        [1] => YWxhZGRpbjpvcGVuc2VzYW1l
    )

### `HTTPHeader::Range()`

Returns an associative array containing the unit and range values:

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

### `HTTPHeader::Referer()`

Returns the result of `parse_url()` on the supplied value:

    Array
    (
        [scheme] => https
        [host] => example.com
        [path] => /en-US/
    )

### `HTTPHeader::Save_Data()`

Returns 0 for falsey values, 1 for truthy values, or `null` if the value is indeterminate.

### `HTTPHeader::TE()`

Returns an array sorted by "q" value:

    Array
    (
        [0] => trailers
        [1] => gzip
        [2] => deflate;q=0.5
    ) 

### `HTTPHeader::Upgrade_Insecure_Requests()`

Returns 0, 1, or `null` if the value is indeterminate.

### `HTTPHeader::User_Agent()`

Returns a string containing the supplied user agent identifier.

### `HTTPHeader::Via()`

Returns an array of proxy identifiers:

    Array
    (
        [0] => 1.0 foo
        [1] => 1.1 bar.example.com
    )

### `HTTPHeader::Want_Digest()`

Returns an array sorted by "q" value: 

    Array
    (
        [0] => sha-256;q=1
        [1] => SHA-512;q=0.3
        [2] => md5;q=0
    )

## Return values

Methods will return `false` if the request header is not present or cannot be parsed.
