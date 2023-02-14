<?php

namespace FT\RequestResponse\Headers;

use FT\RequestResponse\Enums\AuthorizationSchemeTypes;

final class Authorization extends AbstractHeader
{
    public readonly AuthorizationSchemeTypes $auth_scheme;
    public readonly string $credentials;

    public function __construct(string $auth)
    {
        parent::__construct($auth);

        $split = preg_split("/[ ]/", $auth, 2);

        $scheme = AuthorizationSchemeTypes::tryFromValue(htmlspecialchars($split[0]));
        if ($scheme === null) $this->auth_scheme = AuthorizationSchemeTypes::UNKNOWN;
        else $this->auth_scheme = $scheme;

        $this->credentials = htmlspecialchars($split[1]);
    }
}
