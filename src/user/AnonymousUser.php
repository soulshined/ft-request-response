<?php

namespace FT\RequestResponse\User;

use FT\RequestResponse\User\AbstractUser;

final class AnonymousUser extends AbstractUser
{
    public function getUserName(): ?string
    {
        return null;
    }

    public function getPassword(): ?string
    {
        return null;
    }
}
