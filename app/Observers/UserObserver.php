<?php

namespace App\Observers;

use App\Models\User;
use App\Storages\UsersStorage;

class UserObserver
{
    private readonly UsersStorage $usersStorage;

    public function __construct(UsersStorage $usersStorage)
    {
        $this->usersStorage = $usersStorage;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->usersStorage->clean();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->usersStorage->clean();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->usersStorage->clean();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
