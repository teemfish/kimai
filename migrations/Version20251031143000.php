<?php

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
 * @version 2.41
 */
final class Version20251031143000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the invoice template meta table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('kimai2_invoice_templates_meta');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('template_id', 'integer', ['notnull' => true]);
        $table->addColumn('name', 'string', ['length' => 50, 'notnull' => true]);
        $table->addColumn('value', 'text', ['length' => 65535, 'notnull' => false, 'default' => null]);
        $table->addColumn('visible', 'boolean', ['notnull' => true, 'default' => false]);
        $table->addIndex(['template_id'], 'IDX_A165B0555DA0FB8');
        $table->addUniqueIndex(['template_id', 'name'], 'UNIQ_A165B0555DA0FB85E237E06');
        $table->setPrimaryKey(['id']);
        $schema->getTable('kimai2_invoice_templates_meta')->addForeignKeyConstraint('kimai2_invoice_templates', ['template_id'], ['id'], ['onDelete' => 'CASCADE'], 'FK_A165B0555DA0FB8');
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('kimai2_invoice_templates_meta');
        $table->removeForeignKey('FK_A165B0555DA0FB8');

        $schema->dropTable('kimai2_invoice_templates_meta');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
