<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Doctrine;

use App\Doctrine\DsnParserFactory;
use Doctrine\DBAL\Tools\DsnParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DsnParserFactory::class)]
class DsnParserFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        self::assertInstanceOf(DsnParser::class, (new DsnParserFactory())->create());
    }

    public function testMySqlDefaults(): void
    {
        $options = (new DsnParserFactory())->parse('mysql://kimai:password@localhost/kimai');

        self::assertSame('pdo_mysql', $options['driver']);
        self::assertSame('utf8mb4', $options['charset']);
        self::assertSame(['charset' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci'], $options['defaultTableOptions']);
    }

    public function testPostgresOptions(): void
    {
        foreach (['postgres', 'postgresql', 'pgsql'] as $scheme) {
            $options = (new DsnParserFactory())->parse($scheme . '://kimai:password@localhost:5432/kimai?charset=utf8&serverVersion=16');

            self::assertSame('pdo_pgsql', $options['driver']);
            self::assertSame('utf8', $options['charset']);
            self::assertSame('16', $options['serverVersion']);
            self::assertArrayNotHasKey('defaultTableOptions', $options);
        }
    }

    public function testPostgresWithoutCharset(): void
    {
        $options = (new DsnParserFactory())->parse('postgresql://kimai:password@localhost/kimai');

        self::assertArrayNotHasKey('charset', $options);
        self::assertArrayNotHasKey('defaultTableOptions', $options);
    }
}
