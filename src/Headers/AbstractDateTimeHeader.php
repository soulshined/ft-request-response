<?php

namespace FT\RequestResponse\Headers;

use DateTime;

abstract class AbstractDateTimeHeader extends AbstractHeader
{
    public readonly DateTime $date;
    public readonly bool $invalid_date_format;
    public readonly array | false $errors;

    public function __construct(string $header)
    {
        parent::__construct($header);
        $date = DateTime::createFromFormat(DATE_RFC7231, $header);
        $this->invalid_date_format = $date === false;
        $this->errors = DateTime::getLastErrors();

        if (!$this->invalid_date_format) $this->date = $date;
    }
}
