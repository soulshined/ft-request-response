<?php

namespace FT\RequestResponse\Headers;

final class Authorization extends AbstractHeader
{
    public readonly string $auth_scheme;
    public readonly string $credentials;

    public function __construct(string $auth)
    {
        parent::__construct($auth);

        $split = preg_split("/[ ]/", $auth, 2);
        $this->auth_scheme = $split[0];
        $this->credentials = trim($split[1]);
    }
}
