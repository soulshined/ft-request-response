<?php

namespace FT\RequestResponse\Headers;

final class ContentType extends AbstractQualifiedParameterizedHeader
{

    public function __construct(string $type)
    {
        parent::__construct($type);
    }
}
