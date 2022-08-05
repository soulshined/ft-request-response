<?php

namespace FT\RequestResponse;

final class URL
{
    public readonly string $host;
    public readonly string $scheme;
    public readonly ?int $port;
    public readonly ?string $password;
    public readonly ?string $path;
    public readonly ?string $user;
    public readonly ?string $query;
    public readonly ?string $fragment;
    public readonly string $authority;

    public function __construct(string $url)
    {
        $this->host = parse_url($url, PHP_URL_HOST);
        $this->scheme = parse_url($url, PHP_URL_SCHEME);
        $this->port = parse_url($url, PHP_URL_PORT);
        $this->password = parse_url($url, PHP_URL_PASS);
        $this->path = parse_url($url, PHP_URL_PATH);
        $this->user = parse_url($url, PHP_URL_USER);
        $this->fragment = parse_url($url, PHP_URL_FRAGMENT);

        $authority = "";
        if ($this->user)
            $authority .= "$this->user:$this->password@";

        $authority .= $this->host . (isset($this->port) ? ":$this->port" : "");
        $this->authority = $authority;

        $query = parse_url($url, PHP_URL_QUERY);
        $this->query = empty($query) || !$query ? null : $query;
    }

    public static function from(string $url): URL
    {
        return new URL($url);
    }

    public function __toString()
    {
        return sprintf(
            "%s://%s%s%s%s",
            $this->scheme,
            $this->authority,
            $this->path,
            empty($this->query) ? "" : "?" . $this->query,
            empty($this->fragment) ? "" : "#$this->fragment"
        );
    }
}
