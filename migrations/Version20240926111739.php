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
 * @version 2.23
 */
final class Version20240926111739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds created_at and break duration column';
    }

    public function up(Schema $schema): void
    {
        $schema->getTable('kimai2_activities')->addColumn('created_at', 'datetime_immutable', ['notnull' => false]);
        $schema->getTable('kimai2_customers')->addColumn('created_at', 'datetime_immutable', ['notnull' => false]);
        $schema->getTable('kimai2_projects')->addColumn('created_at', 'datetime_immutable', ['notnull' => false]);
        $this->addSql('ALTER TABLE kimai2_timesheet ADD break INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE kimai2_timesheet DROP break');
        $this->addSql('ALTER TABLE kimai2_projects DROP created_at');
        $this->addSql('ALTER TABLE kimai2_customers DROP created_at');
        $this->addSql('ALTER TABLE kimai2_activities DROP created_at');
    }
}
