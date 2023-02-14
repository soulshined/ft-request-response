<?php

namespace FT\RequestResponse\Headers;

final class SecFetchSite extends AbstractHeader
{
    public function __construct(string $site)
    {
        parent::__construct($site);
    }

    public function isCrossSite(): bool
    {
        return $this->toLower() === 'cross-site';
    }

    public function isSameOrigin(): bool
    {
        return $this->toLower() === 'same-origin';
    }

    public function isSameSite(): bool
    {
        return $this->toLower() === 'same-site';
    }

    public function isNone(): bool
    {
        return $this->toLower() === 'none';
    }
}
