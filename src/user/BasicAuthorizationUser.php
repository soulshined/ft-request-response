<?php

namespace FT\RequestResponse\User;

final class BasicAuthorizationUser extends AbstractUser
{

    public function getUserName(): ?string
    {
        if (isset($_SERVER['PHP_AUTH_USER']))
            return htmlspecialchars($_SERVER['PHP_AUTH_USER']);

        if (isset($_SERVER['REMOTE_USER']))
            return htmlspecialchars($_SERVER['REMOTE_USER']);

        if (isset($_SERVER['REDIRECT_REMOTE_USER']))
            return htmlspecialchars($_SERVER['REDIRECT_REMOTE_USER']);

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $header = preg_split("/ /", $_SERVER['HTTP_AUTHORIZATION']);
            if ($header[0] !== 'Basic') return null;

            return preg_split("/:/", base64_decode($header[1]))[0];
        }

        return null;
    }

    public function getPassword(): ?string
    {
        if (isset($_SERVER['PHP_AUTH_PW']))
            return htmlspecialchars($_SERVER['PHP_AUTH_PW']);


        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $header = preg_split("/ /", $_SERVER['HTTP_AUTHORIZATION']);
            if ($header[0] !== 'Basic') return null;

            return preg_split("/:/", base64_decode($header[1]))[1];
        }

        return null;
    }
}
