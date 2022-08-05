<?php

namespace FT\RequestResponse\Headers;

final class Connection extends AbstractMultiValueHeader
{

    public function __construct(string $connection)
    {
        parent::__construct($connection);
    }
}
