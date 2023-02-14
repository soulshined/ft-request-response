<?php

namespace FT\RequestResponse\Headers;

final class ContentEncoding extends AbstractMultiValueHeader
{

    public function __construct(string $encoding)
    {
        parent::__construct($encoding);
    }
}
