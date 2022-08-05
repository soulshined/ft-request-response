<?php

namespace FT\RequestResponse\Enums;

use JsonSerializable;

enum RequestMethods implements JsonSerializable
{
    use EnumTrait;

    case GET;
    case HEAD;
    case POST;
    case PUT;
    case DELETE;
    case CONNECT;
    case OPTIONS;
    case TRACE;
    case PATCH;

    public function jsonSerialize(): mixed
    {
        return $this->name;
    }
}
