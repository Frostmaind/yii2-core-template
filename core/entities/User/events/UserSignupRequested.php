<?php

namespace core\entities\User\events;

use core\entities\User\User;

class UserSignupRequested
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}