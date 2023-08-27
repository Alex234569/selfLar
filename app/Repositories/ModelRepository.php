<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class ModelRepository
{
    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return class-string<Model>
     */
    abstract public function getClassName(): string;

    /**
     * @return Model
     */
    public function newModel(): Model
    {
        /** @var class-string<Model> $className */
        $className = $this->getClassName();

        return new $className();
    }

    public function createQueryBuilder(): Builder
    {
        /** @var class-string<Model> $className */
        $className = $this->getClassName();

        return $className::query();
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param int|string $id
     *
     * @return Model|null
     */
    public function find(int|string $id): ?Model
    {
        /** @var Model|null $item */
        $item = $this->createQueryBuilder()->find($id);

        return $item;
    }

    /**
     * Finds an objects by its primary keys / identifiers.
     *
     * @param array<int|string> $ids the identifiers list
     *
     * @return Collection<int, Model> the objects
     */
    public function findMany(array $ids): Collection
    {
        /** @var Collection<int, Model> $items */
        $items = $this->createQueryBuilder()->findMany($ids);

        return $items;
    }

    /**
     * Finds all objects in the repository.
     *
     * @return Collection<int, Model> the objects
     */
    public function findAll(): Collection
    {
        /** @var Collection<int, Model> $items */
        $items = $this->createQueryBuilder()->get();

        return $items;
    }

    /**
     * Find the first element in table
     *
     * @param positive-int|null $minId for the first element with id >= $minId
     *
     * @return Model|null
     */
    public function first(?int $minId = null): ?Model
    {
        $builder = $this->createQueryBuilder()->orderBy('id');
        if (null !== $minId) {
            $builder->where('id', '>=', $minId);
        }

        /** @var Model|null $item */
        $item = $builder->first();

        return $item;
    }

    /**
     * Find the last element in table
     *
     * @param string $field
     *
     * @return Model|null
     */
    public function last(string $field = 'id'): ?Model
    {
        /** @var Model|null $item */
        $item = $this->createQueryBuilder()->orderByDesc($field)->first();

        return $item;
    }

    /**
     * Delete by ids
     *
     * @param int[]        $ids
     * @param positive-int $chunkSize
     *
     * @return int
     */
    public function deleteByIds(array $ids, int $chunkSize = 100): int
    {
        $qualifiedKeyName = $this->newModel()->getQualifiedKeyName();
        $deletedCnt = 0;

        foreach (array_chunk($ids, $chunkSize) as $chunk) {
            $deletedCnt += $this
                ->createQueryBuilder()
                ->whereIntegerInRaw($qualifiedKeyName, $chunk)
                ->delete();
        }

        return $deletedCnt;
    }
}
