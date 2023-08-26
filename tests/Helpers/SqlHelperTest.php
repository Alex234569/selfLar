<?php

namespace Tests\Helpers;

use App\Helpers\SqlHelper;
use PHPUnit\Framework\TestCase;

class SqlHelperTest extends TestCase
{
    /**
     * @dataProvider stringProvider
     *
     * @covers       \App\Helpers\SqlLimitsHelper::string
     *
     * @param string $string
     * @param string $expected
     *
     * @return void
     */
    public function testStringCutter(string $string, string $expected): void
    {
        self::assertEquals($expected, $result = SqlHelper::string($string, $length = 10));
        self::assertLessThanOrEqual($length, strlen($result));
    }

    /**
     * @return array<array<string>>
     */
    public static function stringProvider(): array
    {
        return [
            ['abcdefghijklmno', 'abcdefghij'],
            ['абвгдеёжзийклмн', 'абвгд'],
            ['abcdeабвгдежmno', 'abcdeаб'],
            ['?/\dаб_г-,dжmno', '?/\dаб_'],
        ];
    }
}
