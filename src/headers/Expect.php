<?php

namespace FT\RequestResponse\Headers;

final class Expect extends AbstractHeader
{

    public function __construct(string $expect)
    {
        parent::__construct($expect);
    }

    public function is100Continue(): bool
    {
        return $this->toLower() === '100-continue';
    }
}
