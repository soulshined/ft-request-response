<?php

namespace FT\RequestResponse\Headers;

final class ECT extends AbstractHeader
{

    public function __construct(string $platform)
    {
        parent::__construct($platform);
    }

    public function isSlow2g(): bool
    {
        return $this->toLower() === 'slow-2g';
    }

    public function is2g(): bool
    {
        return $this->toLower() === '2g';
    }

    public function is3g(): bool
    {
        return $this->toLower() === '3g';
    }

    public function is4g(): bool
    {
        return $this->toLower() === '4g';
    }
}
