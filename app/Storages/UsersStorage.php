<?php

declare(strict_types=1);

namespace App\Storages;

use App\Interfaces\SingletonInterface;
use App\Models\User;
use App\Repositories\UsersRepository;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Support\Collection;

final class UsersStorage implements SingletonInterface
{
    private readonly UsersRepository $repository;

    private readonly CacheContract $cache;

    public function __construct(
        UsersRepository $repository,
        CacheContract $cache
    ) {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return Collection<int, User>
     */
    public function all(): Collection
    {
        return $this->cache->remember(
            'UsersStorage::all',
            (int)CarbonInterval::day()->totalSeconds,
            fn () => $this->repository->findAll(),
        );
    }
}
