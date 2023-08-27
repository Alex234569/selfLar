<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\SingletonInterface;
use App\Models\User;

final class UsersRepository extends ModelRepository implements SingletonInterface
{
    public function getClassName(): string
    {
        return User::class;
    }
}
