<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use DateInterval;
use DateTimeInterface;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

final class DateTimeHelper
{
    /**
     * Split a time between $start & $end on intervals $interval
     *
     * @param DateTimeInterface|string $start    From
     * @param DateTimeInterface|string $end      To
     * @param DateInterval             $interval Interval (in seconds)
     *
     * @return iterable<array<int, CarbonInterface>>> Array with parameters $from & $to
     */
    #[ArrayShape([[
        Carbon::class,
        Carbon::class,
    ]])]
    public static function split(
        DateTimeInterface|string $start,
        DateTimeInterface|string $end,
        DateInterval $interval
    ): iterable {
        $period = new CarbonPeriod($start, $end, $interval);

        $endDate = $period->getEndDate();

        /**
         * @var int    $index
         * @var Carbon $from
         */
        foreach ($period as $index => $from) {
            $from->addSeconds($index);

            /** @var CarbonInterface $to */
            $to = $from->copy()->add($period->getDateInterval());

            if ($from > $endDate) {
                continue;
            }

            if ($to > $endDate) {
                /** @var Carbon $to */
                $to = $endDate;
            }

            yield [$from, $to];
        }
    }

    /**
     * Split the time between $start & $end into intervals of 23 hours 59 minutes 59 seconds.
     *
     * @param DateTimeInterface $start From
     * @param DateTimeInterface $end   To
     *
     * @return iterable<array<int, CarbonInterface>>> Array with parameters $from & $to
     *
     * @throws Exception
     */
    #[ArrayShape([[
        Carbon::class,
        Carbon::class,
    ]])]
    public static function splitByDay(DateTimeInterface $start, DateTimeInterface $end): iterable
    {
        $interval = CarbonInterval::create(0, 0, 0, 0, 23, 59, 59);

        return self::split($start, $end, $interval);
    }
}
