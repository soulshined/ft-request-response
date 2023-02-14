<?php

namespace FT\RequestResponse\Headers;

final class SecFetchMode extends AbstractHeader
{
    public function __construct(string $mode)
    {
        parent::__construct($mode);
    }

    public function isCors(): bool
    {
        return $this->toLower() === 'cors';
    }

    public function isNavigate(): bool
    {
        return $this->toLower() === 'navigate';
    }

    public function isNoCors(): bool
    {
        return $this->toLower() === 'no-cors';
    }

    public function isSameOrigin(): bool
    {
        return $this->toLower() === 'same-origin';
    }

    public function isWebSocket(): bool
    {
        return $this->toLower() === 'websocket';
    }
}
