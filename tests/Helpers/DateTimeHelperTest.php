<?php

namespace Tests\Helpers;

use App\Helpers\DateTimeHelper;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use PHPUnit\Framework\TestCase;

class DateTimeHelperTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testSplit(): void
    {
        $start = '2020-01-01 00:00:00';
        $end = '2020-01-10 12:00:00';
        $interval = CarbonInterval::create(0, 0, 0, 4, 10, 30, 45);

        $expected = [
            // '2020-01-01 00:00:00' => '2020-01-05 10:30:45',
            ['from' => '2020-01-01 00:00:00', 'to' => '2020-01-05 10:30:45'],
            // '2020-01-05 10:30:46' => '2020-01-09 21:01:31',
            ['from' => '2020-01-05 10:30:46', 'to' => '2020-01-09 21:01:31'],
            // '2020-01-09 21:01:32' => '2020-01-10 12:00:00',
            ['from' => '2020-01-09 21:01:32', 'to' => '2020-01-10 12:00:00'],
        ];

        $result = [];
        foreach (DateTimeHelper::split($start, $end, $interval) as [$from, $to]) {
            $result[] = ['from' => $from->format('Y-m-d H:i:s'), 'to' => $to->format('Y-m-d H:i:s')];
        }

        static::assertEquals($expected, $result);
    }

    /**
     * @throws Exception
     */
    public function testSplitByDay(): void
    {
        $start = Carbon::parse('2020-01-01 00:00:00');
        $end = Carbon::parse('2020-01-03 12:00:00');

        $expected = [
            ['from' => '2020-01-01 00:00:00', 'to' => '2020-01-01 23:59:59'],
            ['from' => '2020-01-02 00:00:00', 'to' => '2020-01-02 23:59:59'],
            ['from' => '2020-01-03 00:00:00', 'to' => '2020-01-03 12:00:00'],
        ];

        $result = [];
        foreach (DateTimeHelper::splitByDay($start, $end) as [$from, $to]) {
            $result[] = ['from' => $from->format('Y-m-d H:i:s'), 'to' => $to->format('Y-m-d H:i:s')];
        }

        static::assertEquals($expected, $result);
    }
}
