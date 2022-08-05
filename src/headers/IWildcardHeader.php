<?php

namespace FT\RequestResponse\Headers;

interface IWildcardHeader
{
    public function has_wildcard(): bool;
}
