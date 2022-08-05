<?php

namespace FT\RequestResponse\Headers;

final class TransferEncoding extends AbstractMultiValueHeader
{
    public function __construct(string $encoding)
    {
        parent::__construct($encoding);
    }
}
