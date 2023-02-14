<?php

namespace FT\RequestResponse\Headers;

final class XForwardedFor extends AbstractMultiValueHeader
{
    public function __construct(string $for)
    {
        parent::__construct($for);
    }
}
