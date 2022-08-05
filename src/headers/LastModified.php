<?php

namespace FT\RequestResponse\Headers;

final class LastModified extends AbstractDateTimeHeader
{
    public function __construct(string $lastmodified)
    {
        parent::__construct($lastmodified);
    }
}
