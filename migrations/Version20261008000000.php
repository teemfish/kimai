<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use App\Doctrine\AbstractMigration;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

final class Version20261008000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align the PostgreSQL schema with the entity mappings';
    }

    public function up(Schema $schema): void
    {
        if (!($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform)) {
            $this->preventEmptyMigrationWarning();

            return;
        }

        $schema->getTable('kimai2_activities')->getColumn('visible')->setDefault(true);
        $schema->getTable('kimai2_activities')->getColumn('time_budget')->setDefault(null);
        $schema->getTable('kimai2_activities')->getColumn('budget')->setDefault(null);
        $schema->getTable('kimai2_customers')->getColumn('time_budget')->setDefault(null);
        $schema->getTable('kimai2_customers')->getColumn('budget')->setDefault(null);
        $schema->getTable('kimai2_customers')->getColumn('language')->setDefault(null);
        $schema->getTable('kimai2_invoice_templates')->getColumn('vat')->setDefault(null);
        $schema->getTable('kimai2_projects')->getColumn('time_budget')->setDefault(null);
        $schema->getTable('kimai2_projects')->getColumn('budget')->setDefault(null);
        $schema->getTable('kimai2_activities_rates')->getColumn('activity_id')->setNotnull(true);
        $schema->getTable('kimai2_customers_rates')->getColumn('customer_id')->setNotnull(true);
        $schema->getTable('kimai2_projects_rates')->getColumn('project_id')->setNotnull(true);
        $schema->getTable('kimai2_tags')->getColumn('visible')->setNotnull(true);
        $schema->getTable('kimai2_timesheet')->getColumn('billable')->setNotnull(true);
        $schema->getTable('kimai2_user_preferences')->getColumn('user_id')->setNotnull(true);
        $schema->getTable('kimai2_invoice_templates')->getColumn('vat')->setNotnull(true);
        $schema->getTable('kimai2_timesheet')->getColumn('modified_at')->setType(Type::getType(Types::DATETIME_IMMUTABLE));
        $schema->getTable('kimai2_timesheet')->getColumn('date_tz')->setType(Type::getType(Types::DATE_IMMUTABLE));
        $schema->getTable('kimai2_users')->getColumn('password_requested_at')->setType(Type::getType(Types::DATETIME_IMMUTABLE));
        $schema->getTable('kimai2_working_times')->getColumn('date')->setType(Type::getType(Types::DATE_IMMUTABLE));
        $schema->getTable('kimai2_working_times')->getColumn('approved_at')->setType(Type::getType(Types::DATETIME_IMMUTABLE));
        $tags = $schema->getTable('kimai2_timesheet_tags');
        $tags->renameIndex('IDX_E3284EFEABDD46BE', 'IDX_732EECA9ABDD46BE');
        $tags->renameIndex('IDX_E3284EFEBAD26311', 'IDX_732EECA9BAD26311');
    }

    public function down(Schema $schema): void
    {
        if (!($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform)) {
            $this->preventEmptyMigrationWarning();

            return;
        }

        $schema->getTable('kimai2_activities')->getColumn('visible')->setDefault(null);
        $schema->getTable('kimai2_activities')->getColumn('time_budget')->setDefault(0);
        $schema->getTable('kimai2_activities')->getColumn('budget')->setDefault(0);
        $schema->getTable('kimai2_customers')->getColumn('time_budget')->setDefault(0);
        $schema->getTable('kimai2_customers')->getColumn('budget')->setDefault(0);
        $schema->getTable('kimai2_projects')->getColumn('time_budget')->setDefault(0);
        $schema->getTable('kimai2_projects')->getColumn('budget')->setDefault(0);
        $schema->getTable('kimai2_customers')->getColumn('language')->setDefault('en');
        $schema->getTable('kimai2_invoice_templates')->getColumn('vat')->setDefault(0);
        $schema->getTable('kimai2_activities_rates')->getColumn('activity_id')->setNotnull(false);
        $schema->getTable('kimai2_customers_rates')->getColumn('customer_id')->setNotnull(false);
        $schema->getTable('kimai2_projects_rates')->getColumn('project_id')->setNotnull(false);
        $schema->getTable('kimai2_tags')->getColumn('visible')->setNotnull(false);
        $schema->getTable('kimai2_timesheet')->getColumn('billable')->setNotnull(false);
        $schema->getTable('kimai2_user_preferences')->getColumn('user_id')->setNotnull(false);
        $schema->getTable('kimai2_invoice_templates')->getColumn('vat')->setNotnull(false);
        $schema->getTable('kimai2_timesheet')->getColumn('modified_at')->setType(Type::getType(Types::DATETIME_MUTABLE));
        $schema->getTable('kimai2_timesheet')->getColumn('date_tz')->setType(Type::getType(Types::DATE_MUTABLE));
        $schema->getTable('kimai2_users')->getColumn('password_requested_at')->setType(Type::getType(Types::DATETIME_MUTABLE));
        $schema->getTable('kimai2_working_times')->getColumn('date')->setType(Type::getType(Types::DATE_MUTABLE));
        $schema->getTable('kimai2_working_times')->getColumn('approved_at')->setType(Type::getType(Types::DATETIME_MUTABLE));
        $tags = $schema->getTable('kimai2_timesheet_tags');
        $tags->renameIndex('IDX_732EECA9ABDD46BE', 'IDX_E3284EFEABDD46BE');
        $tags->renameIndex('IDX_732EECA9BAD26311', 'IDX_E3284EFEBAD26311');
    }
}
