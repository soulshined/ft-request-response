<?php

namespace FT\RequestResponse\Headers;

final class AccessControlRequestHeaders extends AbstractMultiValueHeader
{

    public function __construct(string $header)
    {
        parent::__construct($header);
    }
}
