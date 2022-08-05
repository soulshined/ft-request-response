<?php

namespace FT\RequestResponse\Headers;

final class Forwarded extends AbstractMultiValueParameterizedHeader
{

    public function __construct(string $forwarded)
    {
        parent::__construct($forwarded);
    }
}
