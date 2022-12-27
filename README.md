## Install

```composer require ft/request-response```

## Usage

### Requests

```php
$req = new Request;
```

That's it. A request automatically builds all things related to the current request based on `$_SERVER` and request method.

```mermaid
classDiagram
class Request {
  RequestHeaders $headers
  RequestMethods $method
  URL $url
  float $time
  object $parameters
  ?RequestBody $body
  ?string $ip
  AbstractUser $user
  string $protocol

  isPOST() bool
  isGET() bool
  isPUT() bool
  isPATCH() bool
  isDELETE() bool
  isHEAD() bool
  isTRACE() bool
  isCONNECT() bool
  isOPTIONS() bool
  isHTTPS() bool
  isHeaderSet(string $header) bool
  isHeaderSetAndNotEmpty(string $header) bool
  isParameterSet(string $name) bool
  isParameterSetAndNotEmpty(string $name) bool
  hasBody() bool
  containsErroneousParamaters(string ...$expecting) bool
}
```

All parameters are added to the request's `parameters` property *regardless* if they are query parameters or body parameters by way of multipart/form-data or www-form-urlencoded params. (Though, these are also added in the `body` property)

### Responses

```php
$resp = new Response();
```

`Response` is a builder-pattern class

Example:

```php
$resp = new Response();
$resp->statusCode(StatusCodes::HTTP_VERSION_NOT_SUPPORTED)
     ->send();
```

Any time you call a `send*()` method of `Response` it will call `die()`

`Response` is content-type aware, for example, if you call the `sendJson()` method
it will automatically set the content type header for you:

```php
$array = [
    'foo' => 'bar'
];
$resp = new Response();
$resp->sendJson($array);
```

```mermaid
classDiagram
class Response {
  headers(array $headers = []) Response
  contentType(string $mediaType) Response
  statusCode(StatusCodes $code) Response
  statusCodePhrase(string $message) Response
  status(StatusCodes $code, ?string $message = null) Response
  content(mixed $content) Response
  redirect(string $url) never
  redirectIf(string $url, callable $predicate) never
  sendXML(string $xml) never
  sendJson(mixed $json) never
  sendHTML(string $html) never
  sendNoContent(?string $etag = null) never
  sendCreated(?string $uri = null) never
  sendAccepted() never
  sendNotFound(?string $message = null) never
  sendUnauthorized() never
  sendForbidden() never
  sendBadRequest() never
  sendInternalServerError() never
  send() never
}
```