<?php

namespace core\entities\User\events;

use core\entities\User\User;

class UserSignupConfirmed
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}