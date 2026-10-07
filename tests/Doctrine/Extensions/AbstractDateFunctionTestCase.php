<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Doctrine\Extensions;

use App\Entity\Timesheet;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

abstract class AbstractDateFunctionTestCase extends TestCase
{
    /**
     * @return class-string<FunctionNode>
     */
    abstract protected function getFunctionClass(): string;

    #[DataProvider('getPlatforms')]
    public function testSqlGeneration(AbstractPlatform $platform): void
    {
        $class = $this->getFunctionClass();
        $function = strtoupper(substr($class, strrpos($class, '\\') + 1));
        $configuration = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../../../src/Entity'], true);
        $configuration->addCustomDatetimeFunction($function, $class);
        $manager = new EntityManager(DriverManager::getConnection(['driver' => 'pdo_sqlite', 'platform' => $platform]), $configuration);
        $sql = $manager->createQuery('SELECT ' . $function . '(t.begin) FROM ' . Timesheet::class . ' t')->getSQL();

        $expected = $platform instanceof PostgreSQLPlatform && $function !== 'DATE'
            ? 'EXTRACT(' . $function . ' FROM '
            : $function . '(';
        self::assertIsString($sql);
        self::assertStringContainsString($expected, $sql);
        self::assertStringContainsString('start_time', $sql);
        $manager->close();
    }

    /**
     * @return array<array<AbstractPlatform>>
     */
    public static function getPlatforms(): array
    {
        return [[new MySQLPlatform()], [new PostgreSQLPlatform()]];
    }
}
