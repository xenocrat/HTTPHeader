## What is this?

HTTPHeader is a PHP class for inspecting HTTP headers.

## Requirements

* PHP 8.0+

## Usage

Methods for inspecting response headers must be supplied with a string containing one or more headers, or a complete HTTP response.

Examples:

``` php
use xenocrat\HTTPHeader;
$result = HTTPHeader::Server("Server: Apache");
$result = HTTPHeader::Server("Content-Type: text/plain\r\nServer: Apache");
$result = HTTPHeader::Server("HTTP/1.1 200 OK\r\nServer: Apache\r\n\r\nHello, world!");
```

Methods for inspecting request headers can optionally be supplied with a string containing one or more headers, or a complete HTTP request; if not supplied with a string, these methods will attempt to read the value from the `$_SERVER` superglobal.

Examples:

``` php
use xenocrat\HTTPHeader;
$result = HTTPHeader::Accept();
$result = HTTPHeader::Accept("Accept: text/html, application/xhtml+xml");
$result = HTTPHeader::Accept("Accept: text/html\r\nAccept-Encoding: gzip");
```

## Methods

### `extract`

#### Description

``` php
public HTTPHeader::extract(
    string $string
): array
```

Extract all header fields from a string containing multiple headers, or from a complete HTTP message.

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array of arrays containing one entry for each occurence of a field.

#### Examples

``` php
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
```

### `Accept`

#### Description

``` php
public HTTPHeader::Accept(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array sorted by "q" value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => text/html
    [1] => application/xhtml+xml
    [2] => image/webp
    [3] => application/xml;q=0.9
    [4] => */*;q=0.8
)
```

### `Accept_CH`

#### Description

``` php
public HTTPHeader::Accept_CH(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of client hint headers. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.
 

### `Accept_Charset`

#### Description

``` php
public HTTPHeader::Accept_Charset(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array sorted by "q" value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Accept_Encoding`

#### Description

``` php
public HTTPHeader::Accept_Encoding(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array sorted by "q" value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => gzip
    [1] => deflate
)
```

### `Accept_Language`

#### Description

``` php
public HTTPHeader::Accept_Language(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array sorted by "q" value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => en-GB
    [1] => en;q=0.7
    [2] => en-US;q=0.3
)
```

### `Accept_Patch`

#### Description

``` php
public HTTPHeader::Accept_Patch(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of media types. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => application/example
    [1] => text/example;charset=utf-8
)
```

### `Accept_Post`

#### Description

``` php
public HTTPHeader::Accept_Post(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of media types. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => image/webp
    [1] => text/example;charset=utf-8
)
```

### `Accept_Ranges`

#### Description

``` php
public HTTPHeader::Accept_Ranges(
    string $string
): string|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a range unit. Returns `false` if the header field is not present or empty.

### `Access_Control_Allow_Credentials`

#### Description

``` php
public HTTPHeader::Access_Control_Allow_Credentials(
    string $string
): ?bool
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns `true` if the value is "true", or `null` otherwise.

### `Access_Control_Allow_Headers`

#### Description

``` php
public HTTPHeader::Access_Control_Allow_Headers(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of headers. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Accept
    [1] => Upgrade-Insecure-Requests
)
```

### `Access_Control_Allow_Methods`

#### Description

``` php
public HTTPHeader::Access_Control_Allow_Methods(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of method names. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => POST
    [1] => GET
    [2] => OPTIONS
)
```

### `Access_Control_Allow_Origin`

#### Description

``` php
public HTTPHeader::Access_Control_Allow_Origin(
    string $string
): string|array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the string `"*"`, the string `"null"` if the value is "null", or the result of `parse_url()` on the supplied value. Returns `false` if the header field is not present or empty.

### `Access_Control_Expose_Headers`

#### Description

``` php
public HTTPHeader::Access_Control_Expose_Headers(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of headers. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Content-Encoding
)
```

### `Access_Control_Max_Age`

#### Description

``` php
public HTTPHeader::Access_Control_Max_Age(
    string $string
): int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a non-negative integer representing the number of seconds. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Access_Control_Request_Headers`

#### Description

``` php
public HTTPHeader::Access_Control_Request_Headers(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of headers. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Content-Type
)
```

### `Access_Control_Request_Method`

#### Description

``` php
public HTTPHeader::Access_Control_Request_Method(
    string $string = null
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the method name. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Activate_Storage_Access`

#### Description

``` php
public HTTPHeader::Activate_Storage_Access(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array containing the directive and parameters. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Age`

``` php
public HTTPHeader::Age(
    string $string
): int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a non-negative integer representing a time delta in seconds. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Allow`

``` php
public HTTPHeader::Allow(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of method names. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => GET
    [1] => POST
    [2] => HEAD
)
```

### `Alt_Svc`

``` php
public HTTPHeader::Alt_Svc(
    string $string
): string|array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the string `"clear"`, or an array of associative arrays containing the parameters for each alternative service. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Alt_Used`

``` php
public HTTPHeader::Alt_Used(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array containing the host, and port if supplied. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [host] => example.com
    [port] => 80
)
```

### `Authorization`

``` php
public HTTPHeader::Authorization(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array containing the authorization type and parameters. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Basic
    [1] => YWxhZGRpbjpvcGVuc2VzYW1l
)
```

### `Cache_Control`

``` php
public HTTPHeader::Cache_Control(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of directives. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => only-if-cached
)
```

### `Clear_Site_Data`

``` php
public HTTPHeader::Clear_Site_Data(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of directives. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => storage
    [1] => cookies
)
```

### `Connection`

``` php
public HTTPHeader::Connection(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of directives. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => keep-alive
)
```

### `Content_Disposition`

``` php
public HTTPHeader::Content_Disposition(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array containing the content disposition, field name and filename (if supplied). Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [disposition] => attachment
    [filename] => filename.jpg
)
```

### `Content_Encoding`

``` php
public HTTPHeader::Content_Encoding(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of encoding formats in the order in which they were applied. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => deflate
    [1] => gzip
)
```

### `Content_Language`

``` php
public HTTPHeader::Content_Language(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of language tags. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => en-GB
    [1] => en
)
```

### `Content_Length`

``` php
public HTTPHeader::Content_Length(
    string $string = null
): int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the content length in decimal number of octets. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Content_Location`

``` php
public HTTPHeader::Content_Location(
    string $string
): string|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a string. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Content_Range`

``` php
public HTTPHeader::Content_Range(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array containing the unit, range, and size. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [unit] => bytes
    [range] => 200-1000
    [size] => 67589
)
```

### `Content_Security_Policy`

``` php
public HTTPHeader::Content_Security_Policy(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the policy directive and values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Content_Security_Policy_Report_Only`

``` php
public HTTPHeader::Content_Security_Policy_Report_Only(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the policy directive and values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Content_Type`

``` php
public HTTPHeader::Content_Type(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array containing the content type, charset and boundary (if supplied). Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [type] => multipart/form-data
    [boundary] => something
)
```

### `Cookie`

``` php
public HTTPHeader::Cookie(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the cookie names and value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Array
        (
            [0] => Session
            [1] => 0ae5ab57925bcbee58917d552acb4cd4
        )
)
```

### `Cross_Origin_Embedder_Policy`

``` php
public HTTPHeader::Cross_Origin_Embedder_Policy(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a policy directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Cross_Origin_Opener_Policy`

``` php
public HTTPHeader::Cross_Origin_Opener_Policy(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a policy directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Cross_Origin_Resource_Policy`

``` php
public HTTPHeader::Cross_Origin_Resource_Policy(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a policy directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Date`

``` php
public HTTPHeader::Date(
    string $string = null
): \DateTimeImmutable|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a DateTimeImmutable object. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
DateTimeImmutable Object
(
    [date] => 2015-10-21 07:28:00.000000
    [timezone_type] => 2
    [timezone] => GMT
)
```

### `Device_Memory`

``` php
public HTTPHeader::Device_Memory(
    string $string = null
): float|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a non-negative float representing the device memory in GiB. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Digest`

``` php
public HTTPHeader::Digest(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the digest algorithms and values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Downlink`

``` php
public HTTPHeader::Downlink(
    string $string = null
): float|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a non-negative float representing the downlink rate in Mbps. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `DNT`

``` php
public HTTPHeader::DNT(
    string $string = null
): int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the integer `0`, `1`, or `null` if the value is indeterminate. Returns `false` if the header field is not present or empty.

### `ECT`

``` php
public HTTPHeader::ECT(
    string $string = null
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a string identifying the effective connection type, or `null` if the value is indeterminate. Returns `false` if the header field is not present or empty.

### `ETag`

``` php
public HTTPHeader::ETag(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an ETag value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Expect`

``` php
public HTTPHeader::Expect(
    string $string = null
): int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the integer `100` if the value is "100-continue", or `null` otherwise. Returns `false` if the header field is not present or empty.

### `Expires`

``` php
public HTTPHeader::Expires(
    string $string
): \DateTimeImmutable|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a DateTimeImmutable object. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
DateTimeImmutable Object
(
    [date] => 2015-10-21 07:28:00.000000
    [timezone_type] => 2
    [timezone] => GMT
)
```

### `Forwarded`

``` php
public HTTPHeader::Forwarded(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of fields, each containing an associative array of directives. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `From`

``` php
public HTTPHeader::From(
    string $string = null
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a string containing the supplied email address. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Host`

``` php
public HTTPHeader::Host(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array containing the host, and port if supplied. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [host] => example.com
    [port] => 80
)
```

### `If_Match`

``` php
public HTTPHeader::If_Match(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of ETag values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => W/"67ab43"
    [1] => "54ed21"
    [2] => "7892dd"
)
```

### `If_Modified_Since`

``` php
public HTTPHeader::If_Modified_Since(
    string $string = null
): \DateTimeImmutable|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a DateTimeImmutable object. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
DateTimeImmutable Object
(
    [date] => 2015-10-21 07:28:00.000000
    [timezone_type] => 2
    [timezone] => GMT
)
```

### `If_None_Match`

``` php
public HTTPHeader::If_None_Match(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of ETag values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => W/"67ab43"
    [1] => "54ed21"
    [2] => "7892dd"
)
```

### `If_Range`

``` php
public HTTPHeader::If_Range(
    string $string = null
): \DateTimeImmutable|string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a DateTimeImmutable object, or an ETag value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `If_Unmodified_Since`

``` php
public HTTPHeader::If_Unmodified_Since(
    string $string = null
): \DateTimeImmutable|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a DateTimeImmutable object. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
DateTimeImmutable Object
(
    [date] => 2015-10-21 07:28:00.000000
    [timezone_type] => 2
    [timezone] => GMT
)
```

### `Keep_Alive`

``` php
public HTTPHeader::Keep_Alive(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array of parameters. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [timeout] => 5
    [max] => 1000
)
```

### `Last_Modified`

``` php
public HTTPHeader::Last_Modified(
    string $string
): \DateTimeImmutable|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a DateTimeImmutable object. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
DateTimeImmutable Object
(
    [date] => 2015-10-21 07:28:00.000000
    [timezone_type] => 2
    [timezone] => GMT
)
```

### `Link`

``` php
public HTTPHeader::Link(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the link URI and an array of parameters. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Location`

``` php
public HTTPHeader::Location(
    string $string
): string|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a string. Returns `false` if the header field is not present or empty.

### `Max_Forwards`

``` php
public HTTPHeader::Max_Forwards(
    string $string = null
): int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a non-negative integer. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Origin`

``` php
public HTTPHeader::Origin(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the result of `parse_url()` on the supplied value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [scheme] => https
    [host] => example.com
)
```

### `Permissions_Policy`

``` php
public HTTPHeader::Permissions_Policy(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the policy directive and values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Pragma`

``` php
public HTTPHeader::Pragme(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of directives. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Proxy_Authenticate`

``` php
public HTTPHeader::Proxy_Authenticate(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the authentication type and parameters. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Proxy_Authorization`

``` php
public HTTPHeader::Proxy_Authorization(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array containing the authorization type and parameters. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Basic
    [1] => YWxhZGRpbjpvcGVuc2VzYW1l
)
```

### `Range`

``` php
public HTTPHeader::Range(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array containing the unit and ranges. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Referer`

``` php
public HTTPHeader::Referer(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the result of `parse_url()` on the supplied value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [scheme] => https
    [host] => example.com
    [path] => /foo/
)
```

### `Referrer_Policy`

``` php
public HTTPHeader::Referrer_Policy(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Retry_After`

``` php
public HTTPHeader::Retry_After(
    string $string
): \DateTimeImmutable|int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a DateTimeImmutable object, or a non-negative integer representing the delay in seconds. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `RTT`

``` php
public HTTPHeader::RTT(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a non-negative integer representing the approximate round trip time in milliseconds. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Save_Data`

``` php
public HTTPHeader::Save_Data(
    string $string = null
): int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the integer `0` for falsey values, `1` for truthy values, or `null` if the value is indeterminate. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Sec_Fetch_Dest`

``` php
public HTTPHeader::Sec_Fetch_Dest(
    string $string = null
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Sec_Fetch_Mode`

``` php
public HTTPHeader::Sec_Fetch_Mode(
    string $string = null
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Sec_Fetch_Site`

``` php
public HTTPHeader::Sec_Fetch_Site(
    string $string = null
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Sec_Fetch_Storage_Access`

``` php
public HTTPHeader::Sec_Fetch_Storage_Access(
    string $string = null
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Sec_Fetch_User`

``` php
public HTTPHeader::Sec_Fetch_User(
    string $string = null
): ?bool
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns `true` if the value is "?1", or `null` otherwise.

### `Sec_GPC`

``` php
public HTTPHeader::Sec_GPC(
    string $string = null
): ?bool
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns `true` if the value is "1", or `null` otherwise.

### `Sec_Purpose`

``` php
public HTTPHeader::Sec_Purpose(
    string $string = null
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

### `Server`

``` php
public HTTPHeader::Server(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of associative arrays containing the product, version and comment (if supplied). Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Array
        (
            [product] => Apache
            [version] => 2.4.1
            [comment] => Unix
        )
)
```

### `Server_Timing`

``` php
public HTTPHeader::Server_Timing(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of associative arrays containing the metric name, description and duration (if supplied). Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Service_Worker_Navigation_Preload`

``` php
public HTTPHeader::Service_Worker_Navigation_Preload(
    string $string = null
): string|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a string. Returns `false` if the header field is not present or empty.

### `Set_Cookie`

``` php
public HTTPHeader::Set_Cookie(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the cookie name and value, and an associative array of parameter values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Set_Login`

``` php
public HTTPHeader::Set_Login(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the string `"logged-in"`, the string `"logged-out"`, or `null` if the value is indeterminate. Returns `false` if the header field is not present or empty.

### `SourceMap`

``` php
public HTTPHeader::SourceMap(
    string $string
): string|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a string. Returns `false` if the header field is not present or empty.

### `Strict_Transport_Security`

``` php
public HTTPHeader::Strict_Transport_Security(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an associative array of parameter values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [max-age] => 63072000
    [includeSubDomains] => <true|false>
    [preload] => <true|false>
)
```

### `TE`

``` php
public HTTPHeader::TE(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array sorted by "q" value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => trailers
    [1] => gzip
    [2] => deflate;q=0.5
)
```

### `Timing_Allow_Origin`

``` php
public HTTPHeader::Timing_Allow_Origin(
    string $string
): string|array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the string `"*"`, or an array containing the results of `parse_url()` on each of the supplied values. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Tk`

``` php
public HTTPHeader::Tk(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns one of the strings `!`, `?`, `G`, `N`, `T`, `C`, `P`, `D`, `U`, or `null` if the value is indeterminate. Returns `false` if the header field is not present or empty.

### `Trailer`

``` php
public HTTPHeader::Trailer(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of field names. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Expires
    [1] => Date
)
```

### `Transfer_Encoding`

``` php
public HTTPHeader::Transfer_Encoding(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of encoding formats in the order in which they were applied. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => gzip
    [1] => chunked
)
```

### `Upgrade`

``` php
public HTTPHeader::Upgrade(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of protocols in order of preference. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => HTTP/2
    [1] => HTTP/1.1
)
```

### `Upgrade_Insecure_Requests`

``` php
public HTTPHeader::Upgrade_Insecure_Requests(
    string $string = null
): int|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the integer `0`, `1`, or `null` if the value is indeterminate. Returns `false` if the header field is not present or empty.

### `User_Agent`

``` php
public HTTPHeader::User_Agent(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of associative arrays containing the product, version and comment (if supplied). Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Vary`

``` php
public HTTPHeader::Vary(
    string $string
): string|array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the string `"*"`, or an array of field names. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => Cookie
    [1] => Save-Data
)
```

### `Via`

``` php
public HTTPHeader::Via(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of associative arrays containing the details supplied by each proxy. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `Want_Digest`

``` php
public HTTPHeader::Want_Digest(
    string $string = null
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array sorted by "q" value. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
Array
(
    [0] => sha-256;q=1
    [1] => SHA-512;q=0.3
    [2] => md5;q=0
)
```

### `WWW_Authenticate`

``` php
public HTTPHeader::WWW_Authenticate(
    string $string
): array|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns an array of arrays containing the authentication type and parameters. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.

#### Examples

```
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
```

### `X_Content_Type_Options`

``` php
public HTTPHeader::X_Content_Type_Options(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns the string `"nosniff"`, or `null` if the value is indeterminate. Returns `false` if the header field is not present or empty.

### `X_Frame_Options`

``` php
public HTTPHeader::X_Frame_Options(
    string $string
): string|null|false
```

#### Parameters

* _$string_

  A string containing one or more headers, or a complete HTTP message.

#### Return Values

Returns a directive. Returns `false` if the header field is not present or empty, and `null` if the field value is noticeably malformed.
