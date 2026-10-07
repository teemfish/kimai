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

final class Version20180701120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial database structure';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('kimai2_users');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('name', 'string', ['length' => 60, 'notnull' => true]);
        $table->addColumn('mail', 'string', ['length' => 160, 'notnull' => true]);
        $table->addColumn('password', 'string', ['length' => 254, 'notnull' => false, 'default' => null]);
        $table->addColumn('alias', 'string', ['length' => 60, 'notnull' => false, 'default' => null]);
        $table->addColumn('active', 'boolean', ['notnull' => true]);
        $table->addColumn('registration_date', 'datetime', ['notnull' => false, 'default' => null]);
        $table->addColumn('title', 'string', ['length' => 50, 'notnull' => false, 'default' => null]);
        $table->addColumn('avatar', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('roles', 'array', ['notnull' => true]);
        $table->addUniqueIndex(['name'], 'UNIQ_B9AC5BCE5E237E06');
        $table->addUniqueIndex(['mail'], 'UNIQ_B9AC5BCE5126AC48');
        $table->setPrimaryKey(['id']);
        $table = $schema->createTable('kimai2_user_preferences');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('user_id', 'integer', ['notnull' => false, 'default' => null]);
        $table->addColumn('name', 'string', ['length' => 50, 'notnull' => true]);
        $table->addColumn('value', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addIndex(['user_id'], 'IDX_8D08F631A76ED395');
        $table->addUniqueIndex(['user_id', 'name'], 'UNIQ_8D08F631A76ED3955E237E06');
        $table->setPrimaryKey(['id']);
        $table = $schema->createTable('kimai2_customers');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('name', 'string', ['length' => 150, 'notnull' => true]);
        $table->addColumn('number', 'string', ['length' => 50, 'notnull' => false, 'default' => null]);
        $table->addColumn('comment', 'text', ['length' => 65535, 'notnull' => false, 'default' => null]);
        $table->addColumn('visible', 'boolean', ['notnull' => true]);
        $table->addColumn('company', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('contact', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('address', 'text', ['length' => 65535, 'notnull' => false, 'default' => null]);
        $table->addColumn('country', 'string', ['length' => 2, 'notnull' => true]);
        $table->addColumn('currency', 'string', ['length' => 3, 'notnull' => true]);
        $table->addColumn('phone', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('fax', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('mobile', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('mail', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('homepage', 'string', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('timezone', 'string', ['length' => 255, 'notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table = $schema->createTable('kimai2_projects');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('customer_id', 'integer', ['notnull' => false, 'default' => null]);
        $table->addColumn('name', 'string', ['length' => 150, 'notnull' => true]);
        $table->addColumn('order_number', 'text', ['length' => 255, 'notnull' => false, 'default' => null]);
        $table->addColumn('comment', 'text', ['length' => 65535, 'notnull' => false, 'default' => null]);
        $table->addColumn('visible', 'boolean', ['notnull' => true]);
        $table->addColumn('budget', 'decimal', ['precision' => 10, 'scale' => 2, 'notnull' => true]);
        $table->addIndex(['customer_id'], 'IDX_407F12069395C3F3');
        $table->setPrimaryKey(['id']);
        $table = $schema->createTable('kimai2_activities');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('project_id', 'integer', ['notnull' => false, 'default' => null]);
        $table->addColumn('name', 'string', ['length' => 150, 'notnull' => true]);
        $table->addColumn('comment', 'text', ['length' => 65535, 'notnull' => false, 'default' => null]);
        $table->addColumn('visible', 'boolean', ['notnull' => true]);
        $table->addIndex(['project_id'], 'IDX_8811FE1C166D1F9C');
        $table->setPrimaryKey(['id']);
        $table = $schema->createTable('kimai2_timesheet');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('user', 'integer', ['notnull' => false, 'default' => null]);
        $table->addColumn('activity_id', 'integer', ['notnull' => false, 'default' => null]);
        $table->addColumn('start_time', 'datetime', ['notnull' => true]);
        $table->addColumn('end_time', 'datetime', ['notnull' => false, 'default' => null]);
        $table->addColumn('duration', 'integer', ['notnull' => false, 'default' => null]);
        $table->addColumn('description', 'text', ['length' => 65535, 'notnull' => false, 'default' => null]);
        $table->addColumn('rate', 'decimal', ['precision' => 10, 'scale' => 2, 'notnull' => true]);
        $table->addIndex(['user'], 'IDX_4F60C6B18D93D649');
        $table->addIndex(['activity_id'], 'IDX_4F60C6B181C06096');
        $table->setPrimaryKey(['id']);
        $table = $schema->createTable('kimai2_invoice_templates');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('name', 'string', ['length' => 60, 'notnull' => true]);
        $table->addColumn('title', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('company', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('address', 'text', ['length' => 65535, 'notnull' => false, 'default' => null]);
        $table->addColumn('due_days', 'integer', ['notnull' => true]);
        $table->addColumn('vat', 'integer', ['notnull' => false, 'default' => null]);
        $table->addColumn('calculator', 'string', ['length' => 20, 'notnull' => true]);
        $table->addColumn('number_generator', 'string', ['length' => 20, 'notnull' => true]);
        $table->addColumn('renderer', 'string', ['length' => 20, 'notnull' => true]);
        $table->addColumn('payment_terms', 'text', ['length' => 65535, 'notnull' => false, 'default' => null]);
        $table->addUniqueIndex(['name'], 'UNIQ_1626CFE95E237E06');
        $table->setPrimaryKey(['id']);
        $schema->getTable('kimai2_user_preferences')->addForeignKeyConstraint('kimai2_users', ['user_id'], ['id'], ['onDelete' => 'CASCADE'], 'FK_8D08F631A76ED395');
        $schema->getTable('kimai2_projects')->addForeignKeyConstraint('kimai2_customers', ['customer_id'], ['id'], ['onDelete' => 'CASCADE'], 'FK_407F12069395C3F3');
        $schema->getTable('kimai2_activities')->addForeignKeyConstraint('kimai2_projects', ['project_id'], ['id'], ['onDelete' => 'CASCADE'], 'FK_8811FE1C166D1F9C');
        $schema->getTable('kimai2_timesheet')->addForeignKeyConstraint('kimai2_users', ['user'], ['id'], [], 'FK_4F60C6B18D93D649');
        $schema->getTable('kimai2_timesheet')->addForeignKeyConstraint('kimai2_activities', ['activity_id'], ['id'], ['onDelete' => 'CASCADE'], 'FK_4F60C6B181C06096');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('kimai2_invoice_templates');
        $schema->dropTable('kimai2_timesheet');
        $schema->dropTable('kimai2_user_preferences');
        $schema->dropTable('kimai2_users');
        $schema->dropTable('kimai2_activities');
        $schema->dropTable('kimai2_projects');
        $schema->dropTable('kimai2_customers');
    }
}
