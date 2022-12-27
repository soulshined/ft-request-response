<?php

namespace FT\RequestResponse\User;

use FT\RequestResponse\Headers\Authorization;

final class BasicAuthorizationUser extends AbstractUser
{
    private ?string $username = null;
    private ?string $password = null;

    public function __construct(public readonly Authorization $authorization)
    {
        $split = preg_split('/:/', base64_decode($this->authorization->credentials), 2);
        if (!empty($split)) {
            $this->username = $split[0];

            if (count($split) > 1)
                $this->password = $split[1];
        }
    }

    public function getUserName(): ?string
    {
        if (isset($_SERVER['PHP_AUTH_USER']))
            return htmlspecialchars($_SERVER['PHP_AUTH_USER']);

        if (isset($_SERVER['REMOTE_USER']))
            return htmlspecialchars($_SERVER['REMOTE_USER']);

        if (isset($_SERVER['REDIRECT_REMOTE_USER']))
            return htmlspecialchars($_SERVER['REDIRECT_REMOTE_USER']);

        return $this->username;
    }

    public function getPassword(): ?string
    {
        if (isset($_SERVER['PHP_AUTH_PW']))
            return htmlspecialchars($_SERVER['PHP_AUTH_PW']);

        return $this->password;
    }
}
