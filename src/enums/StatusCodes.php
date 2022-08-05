<?php

namespace FT\RequestResponse\Enums;

use FT\RequestResponse\Utils;

enum StatusCodes: int
{
    use EnumTrait;

    // region INFORMATIONAL
    case CONTINUE = 100;
    case SWITCHING_PROTOCOLS = 101;
    case PROCESSING = 102;
    case EARLY_HINTS = 103;
    // endregion INFORMATIONAL

    // region SUCCESSFUL
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NON_AUTHORITATIVE_INFORMATION = 203;
    case NO_CONTENT = 204;
    case RESET_CONTENT = 205;
    case PARTIAL_CONTENT = 206;
    case MULTI_STATUS = 207;
    case ALREADY_REPORTED = 208;
    case IM_USED = 226;
    // endregion SUCCESSFUL

    // region REDIRECTION
    case MULTIPLE_CHOICES = 300;
    case MOVED_PERMANENTLY = 301;
    case FOUND = 302;
    case SEE_OTHER = 303;
    case NOT_MODIFIED = 304;
    case USE_PROXY = 305;
    case UNUSED = 306;
    case TEMPORARY_REDIRECT = 307;
    case PERMANENT_REDIRECT = 308;
    // endregion REDIRECTION

    // region CLIENT ERROR
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case PAYMENT_REQUIRED = 402;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case NOT_ACCEPTABLE = 406;
    case PROXY_AUTHENTICATION_REQUIRED = 407;
    case REQUEST_TIMEOUT = 408;
    case CONFLICT = 409;
    case GONE = 410;
    case LENGTH_REQUIRED = 411;
    case PRECONDITION_FAILED = 412;
    case PAYLOAD_TOO_LARGE = 413;
    case URI_TOO_LONG = 414;
    case UNSUPPORTED_MEDIA_TYPE = 415;
    case RANGE_NOT_SATISIFIED = 416;
    case EXPECTATION_FAILED = 417;
    case IM_A_TEAPOT = 418;
    case MISDIRECTED_REQUEST = 421;
    case UNPROCESSABLE_ENTITY = 422;
    case LOCKED = 423;
    case FAILED_DEPENDENCY = 424;
    case TOO_EARLY = 425;
    case UPGRADE_REQUIRED = 426;
    case PRECONDITION_REQUIRED = 428;
    case TOO_MANY_REQUESTS = 429;
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    // endregion CLIENT ERROR

    // region SERVER ERROR
    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;
    case BAD_GATEWAY = 502;
    case SERVICE_UNAVAILABLE = 503;
    case GATEWAY_TIMEOUT = 504;
    case HTTP_VERSION_NOT_SUPPORTED = 505;
    case VARIANT_ALSO_NEGOTIATES = 506;
    case INSUFFICIENT_STORAGE = 507;
    case LOOP_DETECTED = 508;
    case NOT_EXTENDED = 510;
    case NETWORK_AUTHENTICATION_REQUIRED = 511;
    // endregion SERVER ERROR

    public function getPhrase(): string
    {
        return ucwords(strtolower(join(" ", preg_split("/\_/", $this->name))));
    }

    public function isInformational(): bool
    {
        return $this->is1xx();
    }

    public function is1xx(): bool
    {
        return Utils::is_in_range($this->value, 100, 199);
    }

    public function isSuccessful(): bool
    {
        return StatusCodes::is2xx();
    }

    public function is2xx(): bool
    {
        return Utils::is_in_range($this->value, 200, 299);
    }

    public function isRedirection(): bool
    {
        return StatusCodes::is3xx();
    }

    public function is3xx(): bool
    {
        return Utils::is_in_range($this->value, 300, 399);
    }

    public function isClientError(): bool
    {
        return StatusCodes::is4xx();
    }

    public function is4xx(): bool
    {
        return Utils::is_in_range($this->value, 400, 499);
    }

    public function isServerError(): bool
    {
        return StatusCodes::is5xx();
    }

    public function is5xx(): bool
    {
        return Utils::is_in_range($this->value, 500, 599);
    }

    /**
     * inclusive
     */
    public static function is_in_range(int $code, StatusCodeRanges $range): bool
    {
        return Utils::is_in_range($code, $range->getMin(), $range->getMax());
    }

}
