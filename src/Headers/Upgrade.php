<?php

namespace FT\RequestResponse\Headers;

final class Upgrade extends AbstractMultiValueHeader
{
    public function __construct(string $upgrade)
    {
        parent::__construct($upgrade);
    }
}
