<?php

namespace FT\RequestResponse\Enums;

use JsonSerializable;

enum RequestBodyTypes implements JsonSerializable
{
    use EnumTrait;

    case JSON;
    case XML;
    case WWW_FORM_URLENCODED;
    case MULTIPART_FORMDATA;
    case STRING;

    public function jsonSerialize(): mixed
    {
        return $this->name;
    }
}
