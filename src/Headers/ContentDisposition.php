<?php

namespace FT\RequestResponse\Headers;

final class ContentDisposition extends AbstractQualifiedParameterizedHeader
{

    public function __construct(string $content)
    {
        parent::__construct($content);
    }
}
