<?php

namespace FT\RequestResponse\Headers;

final class SecChUa extends AbstractMultiValueHeader
{

    public function __construct(string $header)
    {
        parent::__construct($header);
    }
}
