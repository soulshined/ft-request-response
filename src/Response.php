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

    public function content(mixed $content): Response
    {
        $this->body = $content;
        return $this;
    }

    public function sendXML(string $xml)
    {
        $this->contentType = 'application/xml';
        $this->body = $xml;
        $this->send();
    }

    public function sendJson(mixed $json)
    {
        $this->contentType = 'application/json';
        $this->body = json_encode($json);
        $this->send();
    }

    public function sendHTML(string $html)
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

    public function sendNoContent()
    {
        $this->statusCode(StatusCodes::NO_CONTENT)
            ->send();
    }

    private function sendHeaders()
    {
        if (key_exists('Content-Type', $this->headers))
            $this->contentType = null;

        foreach ($this->headers as $key => $value)
            header($key . ': ' . $value);
    }

    public function send()
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
