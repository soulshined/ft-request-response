<?php

namespace FT\RequestResponse\Enums;

enum StatusCodeRanges: int
{

    use EnumTrait;

    /**
     * 100-199
     */
    case INFORMATIONAL = 100;
    /**
     * 200-299
     */
    case SUCCESSFUL = 200;
    /**
     * 300-399
     */
    case REDIRECTION = 300;
    /**
     * 400-499
     */
    case CLIENT_ERROR = 400;
    /**
     * 500-599
     */
    case SERVER_ERROR = 500;

    public function getMin(): int
    {
        return $this->value;
    }

    public function getMax(): int
    {
        return $this->value + 99;
    }

}
