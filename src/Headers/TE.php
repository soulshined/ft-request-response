<?php

namespace FT\RequestResponse\Headers;

final class TE extends AbstractMultiValueWeightedHeader
{

    public function __construct(string $te)
    {
        parent::__construct($te);
    }
}
