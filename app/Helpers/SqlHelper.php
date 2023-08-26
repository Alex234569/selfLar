<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class SqlHelper
{
    /**
     * Get full sql query with bindings
     *
     * @param Builder|QueryBuilder $builder
     *
     * @return string
     *
     * @throws Exception
     */
    public static function getSqlByBuilder(Builder|QueryBuilder $builder): string
    {
        $sql = $builder->toSql();

        preg_match_all('/[?]/', $sql, $matches);

        if (isset($matches[0]) && count($matches[0]) !== count($builder->getBindings())) {
            throw new Exception('Idk how, but we cant get a full sql string.');
        }

        return vsprintf(str_replace(['%', '?'], ['%%', '\'%s\''], $sql), $builder->getBindings());
    }

    /**
     * Sub string with required length
     *
     * @param string $text
     * @param int    $length
     *
     * @return string
     */
    public static function string(string $text, int $length): string
    {
        return mb_strcut($text, 0, $length, 'UTF-8');
    }
}
