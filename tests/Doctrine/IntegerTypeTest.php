<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Doctrine;

use App\Doctrine\IntegerType;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(IntegerType::class)]
class IntegerTypeTest extends TestCase
{
    public function testValidIntegers(): void
    {
        $type = new IntegerType();
        foreach ([null, 0, 1, true, false, '42', '00042', '02147483647', -2147483648, 2147483647] as $value) {
            self::assertSame($value, $type->convertToDatabaseValue($value, new PostgreSQLPlatform()));
        }
    }

    #[DataProvider('getInvalidIntegers')]
    public function testInvalidIntegers(int|string $value): void
    {
        $this->expectException(ConversionException::class);
        (new IntegerType())->convertToDatabaseValue($value, new PostgreSQLPlatform());
    }

    /**
     * @return array<array<int|string>>
     */
    public static function getInvalidIntegers(): array
    {
        return [[2147483648], [-2147483649], [PHP_INT_MAX], ['invalid']];
    }

    public function testMySqlValues(): void
    {
        self::assertSame(PHP_INT_MAX, (new IntegerType())->convertToDatabaseValue(PHP_INT_MAX, new MySQLPlatform()));
    }
}
