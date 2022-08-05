<?php

namespace FT\RequestResponse\Headers;

final class Accept extends AbstractMultiValueWeightedHeader implements IWildcardHeader
{

    public function __construct(string $accept)
    {
        parent::__construct($accept);
    }

    public function has_wildcard(): bool
    {
        return $this->has("*/*");
    }
}
