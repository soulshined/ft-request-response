<?php

namespace FT\RequestResponse\Headers;

final class Trailer extends AbstractMultiValueHeader
{
    public function __construct(string $trailer)
    {
        parent::__construct($trailer);
    }
}
