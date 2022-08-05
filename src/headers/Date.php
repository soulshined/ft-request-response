<?php

namespace FT\RequestResponse\Headers;

final class Date extends AbstractDateTimeHeader
{
    public function __construct(string $date)
    {
        parent::__construct($date);
    }
}
