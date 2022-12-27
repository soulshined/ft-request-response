<?php

namespace FT\RequestResponse;

use FT\RequestResponse\Enums\RequestMethods;
use FT\RequestResponse\Enums\StatusCodes;
use FT\RequestResponse\Headers\Authorization;
use FT\RequestResponse\User\AbstractUser;
use FT\RequestResponse\User\AnonymousUser;
use FT\RequestResponse\User\BasicAuthorizationUser;

final class Utils
{

    public static function is_in_range(mixed $val, mixed $min, mixed $max)
    {
        return $val >= $min && $val <= $max;
    }


    public static function array_find($array, callable $callable)
    {
        foreach ($array as $val) {
            if (call_user_func($callable, $val)) return $val;
        }

        return null;
    }

    public static function array_some($array, callable $callable) {
        foreach ($array as $val) {
            if (call_user_func($callable, $val)) return true;
        }

        return false;
    }

    public static function get_user_details(?Authorization $authorization): ?AbstractUser
    {
        if ($authorization?->auth_scheme->isBasic())
            return new BasicAuthorizationUser($authorization);

        return new AnonymousUser;
    }

    public static function die_if_not_request_method(RequestMethods ...$methods)
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) {
            http_response_code(StatusCodes::NOT_IMPLEMENTED->value);
            die();
        }
    }

    public static function is_basic_user_request_headers_set()
    {
        return !(empty($_SERVER['HTTP_USER_AGENT'])
            || empty($_SERVER['REMOTE_ADDR'])
            || empty($_SERVER['HTTP_ACCEPT'])
            || empty($_SERVER['HTTP_ACCEPT_LANGUAGE']));
    }

    public static function is_valid_host(...$hosts)
    {
        return in_array($_SERVER['HTTP_HOST'], $hosts);
    }
}
