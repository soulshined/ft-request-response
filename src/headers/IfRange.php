<?php

namespace FT\RequestResponse\Headers;

use DateTime;

final class IfRange extends AbstractHeader
{
    public readonly mixed $value;
    private bool $is_date = false;

    public function __construct(string $ifrange)
    {
        parent::__construct($ifrange);
        $value = DateTime::createFromFormat(DATE_RFC7231, $ifrange);
        $this->is_date = $value !== false;

        if ($this->is_date) $this->value = $value;
        else $this->value = $ifrange;
    }

    public function isDate(): bool
    {
        return $this->is_date;
    }

    public function isEtag(): bool
    {
        return !$this->is_date;
    }
}
