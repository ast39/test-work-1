<?php

namespace App\Http\Services;

use App\Exceptions\UserNotFoundException;
use App\Models\User;


class UserService {

    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function getByEmail(string $email): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
