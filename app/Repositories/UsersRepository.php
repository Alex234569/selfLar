<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class UsersRepository extends ModelRepository
{
    public function getClassName(): string
    {
        return User::class;
    }
}
