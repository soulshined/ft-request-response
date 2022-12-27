<?php

namespace FT\RequestResponse;

use FT\RequestResponse\Enums\StatusCodes;

final class Response
{
    private ?string $contentType;
    private mixed $body = null;
    private StatusCodes $statusCode = StatusCodes::OK;
    private ?string $statusMessage = null;
    private array $headers = [];

    public function __construct()
    {
        $this->contentType = "text/plain";
        ini_set('default_mimetype', '');
    }

    public function headers(array $headers = []): Response
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function contentType(string $mediaType): Response
    {
        $this->contentType = $mediaType;
        return $this;
    }

    public function statusCode(StatusCodes $code): Response
    {
        $this->statusCode = $code;
        return $this;
    }

    public function statusCodePhrase(string $message): Response
    {
        $this->statusMessage = $message;
        return $this;
    }

    public function status(StatusCodes $code, ?string $message = null) : Response {
        $this->statusCode = $code;
        $this->statusMessage = $message;
        return $this;
    }

    public function content(mixed $content): Response
    {
        $this->body = $content;
        return $this;
    }

    public function sendXML(string $xml) : never
    {
        $this->contentType = 'application/xml';
        $this->body = $xml;
        $this->send();
    }

    public function sendJson(object | array | string $json) : never
    {
        $this->contentType = 'application/json';
        if (is_string($json))
            $this->body = $json;
        else $this->body = json_encode($json, JSON_THROW_ON_ERROR);
        $this->send();
    }

    public function sendHTML(string $html) : never
    {
        $this->contentType = 'text/html';
        $this->body = $html;
        $this->send();
    }

    public function redirect(string $url)
    {
        $this->headers['Location'] = $url;
        $this->sendHeaders();
        exit;
    }

    public function redirectIf(string $url, callable $predicate) {
        if (call_user_func($predicate) === true)
            $this->redirect($url);
    }

    public function sendNoContent(?string $etag = null) : never
    {
        if (!is_null($etag))
            $this->headers['ETag'] = $etag;

        $this->body = null;
        $this->statusCode(StatusCodes::NO_CONTENT)
            ->send();
    }

    public function sendCreated(?string $uri = null) : never {
        if (!is_null($uri))
            $this->headers['Location'] = $uri;
        $this->statusCode(StatusCodes::CREATED)->send();
    }

    public function sendAccepted() : never {
        $this->status(StatusCodes::ACCEPTED)->send();
    }

    public function sendNotFound(?string $message = null) : never {
        $this->status(StatusCodes::NOT_FOUND, $message)->send();
    }

    public function sendUnauthorized(?string $message = null): never
    {
        $this->status(StatusCodes::UNAUTHORIZED, $message)->send();
    }

    public function sendForbidden(?string $message = null): never
    {
        $this->status(StatusCodes::FORBIDDEN, $message)->send();
    }

    public function sendBadRequest(): never
    {
        $this->statusCode(StatusCodes::BAD_REQUEST)->send();
    }

    public function sendInternalServerError(): never
    {
        $this->status(StatusCodes::INTERNAL_SERVER_ERROR)->send();
    }

    private function sendHeaders()
    {
        if (key_exists('Content-Type', $this->headers))
            $this->contentType = null;

        if (key_exists('Location', $this->headers) && !$this->statusCode->is3xx()) {
            header('Location: ' . $this->headers['Location'], true, $this->statusCode->value);
            unset($this->headers['Location']);
        }

        foreach ($this->headers as $key => $value)
            header($key . ': ' . $value);
    }

    public function send() : never
    {
        $this->sendHeaders();

        if ($this->contentType !== null)
            header("Content-Type: " . $this->contentType);

        if (isset($this->statusMessage)) {
            header($_SERVER['SERVER_PROTOCOL'] . " {$this->statusCode->value} $this->statusMessage");
            die($this->body);
        }

        http_response_code($this->statusCode->value);
        die($this->body);
    }
}
