<?php

namespace FT\RequestResponse\Headers;

final class IfModifiedSince extends AbstractDateTimeHeader
{
    public function __construct(string $ifmodified)
    {
        parent::__construct($ifmodified);
    }
}
