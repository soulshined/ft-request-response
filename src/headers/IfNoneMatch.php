<?php

namespace FT\RequestResponse\Headers;

final class IfNoneMatch extends AbstractMultiValueHeader implements IWildcardHeader
{
    public function __construct(string $ifmatch)
    {
        parent::__construct($ifmatch);
    }

    public function has_wildcard(): bool
    {
        return count($this->directives) > 0 && trim($this->directives[0]) === '*';
    }
}
