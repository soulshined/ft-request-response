<?php

namespace FT\RequestResponse;

use DateTime;
use FT\RequestResponse\Enums\RequestMethods;
use FT\RequestResponse\Headers\AbstractDateTimeHeader;
use FT\RequestResponse\Headers\AbstractHeader;
use FT\RequestResponse\Headers\AbstractMultiValueHeader;
use FT\RequestResponse\Headers\AbstractMultiValueParameterizedHeader;
use FT\RequestResponse\Headers\AbstractMultiValueQualifiedParameterizedHeader;
use FT\RequestResponse\Headers\AbstractMultiValueWeightedHeader;
use FT\RequestResponse\Headers\AbstractParameterizedHeader;
use FT\RequestResponse\Headers\AbstractQualifiedParameterizedHeader;
use FT\RequestResponse\Headers\RequestHeaders;
use FT\RequestResponse\User\AbstractUser;
use FT\RequestResponse\User\BasicAuthorizationUser;
use FT\RequestResponse\Utils;
use JsonSerializable;

final class Request implements JsonSerializable
{
    private array $_;
    public readonly RequestHeaders $headers;
    public readonly RequestMethods $METHOD;
    public readonly URL $url;
    public readonly float $time;
    public readonly object $parameters;
    public readonly ?RequestBody $body;
    public readonly ?string $ip;
    public readonly AbstractUser $user;
    public readonly string $protocol;

    // region INIT
    public function __construct()
    {
        $this->_ = $_SERVER;
        $this->METHOD = RequestMethods::tryFromName($this->_['REQUEST_METHOD']);
        $this->time = $this->_['REQUEST_TIME_FLOAT'];
        $this->user = Utils::get_user_details();
        $this->protocol = htmlspecialchars($this->_['SERVER_PROTOCOL']);

        $client_ip = null;
        $ip_headers = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($ip_headers as $header)
            if (key_exists($header, $this->_))
                $client_ip = htmlspecialchars($this->_[$header]);

        $this->ip = $client_ip;
        $this->parseHeaders();
        $this->parseURL();
        $this->parseParameters();
        $this->parseBody();
    }

    private function parseURL()
    {
        $protocol = $this->isHTTPS() ? 'https://' : 'http://';
        $authority = "";

        if ($this->user instanceof BasicAuthorizationUser)
            $authority .= "{$this->user->getUserName()}:{$this->user->getPassword()}@";

        $server_name = htmlspecialchars($this->_['SERVER_NAME']);

        $authority .= $server_name;
        if (empty($authority))
            $authority = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);

        if (!str_contains($authority, "$server_name:")) {
            $port = $this->_['SERVER_PORT'];

            if (!empty($port))
                $authority .= ":$port";
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (empty(trim($path))) $path = "/";
        if (trim($path[0]) !== '/') $path = "/$path";

        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $query = $query ? "?$query" : "";

        $this->url = new URL($protocol . $authority . $path . $query);
    }

    private function parseBody()
    {
        $body = @file_get_contents("php://input") ?: null;
        if ($body === null && empty($_FILES))
            $this->body = null;
        else $this->body = new RequestBody($body ?? "", $this->headers->content_type);
    }

    private function parseHeaders()
    {
        $headers = [];

        foreach ($this->_ as $key => $value) {
            if (!str_starts_with($key, 'HTTP_')) continue;

            $headers[str_replace("HTTP_", "", strtoupper(str_replace("-", "_", $key)))] = $value;
        }
        if (function_exists('apache_request_headers')) {

            foreach (apache_request_headers() as $key => $value)
                $headers[str_replace("HTTP_", "", strtoupper(str_replace("-", "_", $key)))] = $value;
        }

        $this->headers = new RequestHeaders($headers);
    }

    private function parseParameters()
    {
        $this->parameters = new class { };

        parse_str($this->url->query ?? "", $params);

        foreach ($params as $key => $value)
            $this->parameters->$key = $value;

        foreach (array_keys($GLOBALS["_" . $this->METHOD->name]) as $key) {
            if (!isset($this->parameters->$key))
                $this->parameters->$key = $GLOBALS["_" . $this->METHOD->name][$key];
        }
    }
    // endregion INIT

    // region ACCESSORS
    public function isHTTPS(): bool
    {
        if (function_exists('apache_getenv'))
            return apache_getenv('HTTPS');

        $https = key_exists('HTTPS', $this->_)
            ? htmlspecialchars("{$this->_['HTTPS']}")
            : null;
        $httpfwd = key_exists('HTTP_X_FORWARDED_PROTO', $this->_)
            ? htmlspecialchars("{$this->_['HTTP_X_FORWARDED_PROTO']}")
            : null;
        $httpfend = key_exists('HTTP_FRONT_END_HTTPS', $this->_)
            ? htmlspecialchars("{$this->_['HTTP_FRONT_END_HTTPS']}")
            : null;

        return filter_var($https, FILTER_VALIDATE_BOOLEAN)
            || filter_var($httpfwd, FILTER_VALIDATE_BOOLEAN)
            || filter_var($httpfend, FILTER_VALIDATE_BOOLEAN);
    }

    public function isHeaderSet(string $header): bool
    {
        $header = str_replace("-", "_", strtolower($header));
        if (str_starts_with($header, 'http_')) $header = substr($header, 4);

        return isset($this->headers->$header);
    }

    public function isHeaderSetAndNotEmpty(string $header): bool
    {
        $header = str_replace("-", "_", strtolower($header));
        if (str_starts_with($header, 'http_')) $header = substr($header, 4);

        if (!isset($this->headers->$header)) return false;

        $h = $this->headers->$header;

        if ($h instanceof AbstractDateTimeHeader)
            return $h->date !== null;
        else if ($h instanceof AbstractHeader)
            return strlen(trim(strtolower($h->raw))) > 0;
        else if (
            $h instanceof AbstractMultiValueHeader ||
            $h instanceof AbstractParameterizedHeader ||
            $h instanceof AbstractMultiValueWeightedHeader ||
            $h instanceof AbstractMultiValueParameterizedHeader ||
            $h instanceof AbstractMultiValueQualifiedParameterizedHeader
        )
            return $h->count > 0;
        else if ($h instanceof AbstractQualifiedParameterizedHeader)
            return isset($h->directive);
        else if ($h instanceof string)
            return strlen(trim(strtolower($h))) > 0;
        else false;
    }

    public function isParameterSet(string $name): bool
    {
        return property_exists($this->parameters, $name);
    }

    public function isParameterSetAndNotEmpty(string $name): bool
    {
        if (!$this->isParameterSet($name)) return false;

        $val = $this->parameters->$name;

        if (is_array($val)) return !empty($val);
        else if (is_object($val)) return count(get_object_vars($val)) > 0;

        return strlen(trim("$val")) !== 0;
    }

    public function isPOST(): bool
    {
        return $this->METHOD === RequestMethods::POST;
    }

    public function isGET(): bool
    {
        return $this->METHOD === RequestMethods::GET;
    }

    public function isPUT(): bool
    {
        return $this->METHOD === RequestMethods::PUT;
    }

    public function isPATCH(): bool
    {
        return $this->METHOD === RequestMethods::PATCH;
    }

    public function isDELETE(): bool
    {
        return $this->METHOD === RequestMethods::DELETE;
    }

    public function isHEAD(): bool
    {
        return $this->METHOD === RequestMethods::HEAD;
    }

    public function isTRACE(): bool
    {
        return $this->METHOD === RequestMethods::TRACE;
    }

    public function isCONNECT(): bool
    {
        return $this->METHOD === RequestMethods::CONNECT;
    }

    public function isOPTIONS(): bool
    {
        return $this->METHOD === RequestMethods::OPTIONS;
    }

    public function hasBody() : bool {
        return $this->body !== null;
    }

    public function containsErroneousParamaters(string ...$expecting)
    {
        $props = get_object_vars($this->parameters);

        foreach ($props as $key => $value)
            if (!in_array($key, $expecting)) return true;

        return false;
    }
    // endregion ACCESSORS

    public function __toString()
    {
        $headers = [];

        foreach (get_object_vars($this->headers) as $var) {
            if (isset($this->headers->$var))
                $headers[$var] = $this->headers->$var;
        }

        return sprintf(
            "[%s] %s %s\n%s\nContent: %s",
            (new DateTime(strtotime($this->time)))->format('D M d H:i:s Y'),
            $this->METHOD->name,
            $this->url,
            join("\n", array_map(fn ($k, $v) => "$k: $v", array_keys($headers), array_values($headers))),
            $this->body
        );
    }

    public function jsonSerialize(): mixed
    {
        $_this = $this;
        unset($_this->_);
        return $_this;
    }
}
