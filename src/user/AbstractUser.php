<?php

    namespace FT\RequestResponse\User;

    abstract class AbstractUser {

        public abstract function getUserName() : ?string;
        public abstract function getPassword() : ?string;

    }

?>