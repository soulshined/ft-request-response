<?php

namespace FT\RequestResponse\Headers;

abstract class AbstractHeader
{

    public readonly string $raw;

    protected function __construct(string $value)
    {
        $this->raw = $value;
    }

    protected function toLower(): string
    {
        return strtolower(trim($this->raw));
    }

    public function __toString() {
        return $this->raw;
    }

}
