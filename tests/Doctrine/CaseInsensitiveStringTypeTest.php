<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Doctrine;

use App\Doctrine\CaseInsensitiveStringType;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CaseInsensitiveStringType::class)]
class CaseInsensitiveStringTypeTest extends TestCase
{
    public function testSqlDeclaration(): void
    {
        $type = new CaseInsensitiveStringType();

        self::assertSame('CITEXT', $type->getSQLDeclaration(['length' => 180], new PostgreSQLPlatform()));
        self::assertSame('VARCHAR(180)', $type->getSQLDeclaration(['length' => 180], new MySQLPlatform()));
    }
}
