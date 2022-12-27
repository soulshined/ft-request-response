<?php

namespace FT\RequestResponse\Enums;

use JsonSerializable;

enum AuthorizationSchemeTypes: string implements JsonSerializable
{
    use EnumTrait;

    case BASIC = 'Basic';
    case BEARER = 'Bearer';
    case DIGEST = 'Digest';
    case HOBA = 'HOBA';
    case MUTUAL = 'Mutual';
    case NEGOTIATE = 'Negotiate';
    case OAUTH = 'OAuth';
    case SCRAM_SHA_1 = 'SCRAM-SHA-1';
    case SCRAM_SHA_256 = 'SCRAM-SHA-256';
    case VAPID = 'vapid';
    case UNKNOWN = 'unknown';

    public function isBasic() : bool {
        return $this === self::BASIC;
    }

    public function isBearer() : bool {
        return $this === self::BEARER;
    }

    public function isDigest() : bool {
        return $this === self::DIGEST;
    }

    public function isHOBA() : bool {
        return $this === self::HOBA;
    }

    public function isMutal() : bool {
        return $this === self::MUTUAL;
    }

    public function isNegotiate() : bool {
        return $this === self::NEGOTIATE;
    }

    public function isOAuth() : bool {
        return $this === self::OAUTH;
    }

    public function isScramSha1() : bool {
        return $this === self::SCRAM_SHA_1;
    }

    public function isScramSha256() : bool {
        return $this === self::SCRAM_SHA_256;
    }

    public function isVapid() : bool {
        return $this === self::VAPID;
    }

    public function isUnknownOrNotYetImplemented() : bool {
        return $this === self::UNKNOWN;
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}