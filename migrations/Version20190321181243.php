<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use App\Doctrine\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create the system configuration table.
 *
 * @version 0.9
 */
final class Version20190321181243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create system configuration table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('kimai2_configuration');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('name', 'string', ['length' => 100, 'notnull' => true]);
        $table->addColumn('value', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addUniqueIndex(['name'], 'UNIQ_1C5D63D85E237E06');
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('kimai2_configuration');
    }
}
