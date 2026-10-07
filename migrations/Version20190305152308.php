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
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;

/**
 * - rename mail to email in customer table
 * - converts all decimal to float values, as decimals are treated as string in PHP:
 *   https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html#decimal
 *
 * @version 0.9
 */
final class Version20190305152308 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_activities ALTER COLUMN fixed_rate TYPE DOUBLE PRECISION USING fixed_rate::DOUBLE PRECISION, ALTER COLUMN fixed_rate DROP NOT NULL, ALTER COLUMN fixed_rate DROP DEFAULT, ALTER COLUMN hourly_rate TYPE DOUBLE PRECISION USING hourly_rate::DOUBLE PRECISION, ALTER COLUMN hourly_rate DROP NOT NULL, ALTER COLUMN hourly_rate DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_activities CHANGE fixed_rate fixed_rate DOUBLE PRECISION DEFAULT NULL, CHANGE hourly_rate hourly_rate DOUBLE PRECISION DEFAULT NULL');
        }
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_customers RENAME COLUMN mail TO email');
            $this->addSql('ALTER TABLE kimai2_customers ALTER COLUMN email TYPE VARCHAR(255) USING email::VARCHAR(255), ALTER COLUMN email DROP NOT NULL, ALTER COLUMN email DROP DEFAULT, ALTER COLUMN fixed_rate TYPE DOUBLE PRECISION USING fixed_rate::DOUBLE PRECISION, ALTER COLUMN fixed_rate DROP NOT NULL, ALTER COLUMN fixed_rate DROP DEFAULT, ALTER COLUMN hourly_rate TYPE DOUBLE PRECISION USING hourly_rate::DOUBLE PRECISION, ALTER COLUMN hourly_rate DROP NOT NULL, ALTER COLUMN hourly_rate DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_customers CHANGE mail email VARCHAR(255) DEFAULT NULL, CHANGE fixed_rate fixed_rate DOUBLE PRECISION DEFAULT NULL, CHANGE hourly_rate hourly_rate DOUBLE PRECISION DEFAULT NULL');
        }
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_projects ALTER COLUMN budget TYPE DOUBLE PRECISION USING budget::DOUBLE PRECISION, ALTER COLUMN budget SET NOT NULL, ALTER COLUMN budget DROP DEFAULT, ALTER COLUMN fixed_rate TYPE DOUBLE PRECISION USING fixed_rate::DOUBLE PRECISION, ALTER COLUMN fixed_rate DROP NOT NULL, ALTER COLUMN fixed_rate DROP DEFAULT, ALTER COLUMN hourly_rate TYPE DOUBLE PRECISION USING hourly_rate::DOUBLE PRECISION, ALTER COLUMN hourly_rate DROP NOT NULL, ALTER COLUMN hourly_rate DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_projects CHANGE budget budget DOUBLE PRECISION NOT NULL, CHANGE fixed_rate fixed_rate DOUBLE PRECISION DEFAULT NULL, CHANGE hourly_rate hourly_rate DOUBLE PRECISION DEFAULT NULL');
        }
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_timesheet ALTER COLUMN rate TYPE DOUBLE PRECISION USING rate::DOUBLE PRECISION, ALTER COLUMN rate SET NOT NULL, ALTER COLUMN rate DROP DEFAULT, ALTER COLUMN fixed_rate TYPE DOUBLE PRECISION USING fixed_rate::DOUBLE PRECISION, ALTER COLUMN fixed_rate DROP NOT NULL, ALTER COLUMN fixed_rate DROP DEFAULT, ALTER COLUMN hourly_rate TYPE DOUBLE PRECISION USING hourly_rate::DOUBLE PRECISION, ALTER COLUMN hourly_rate DROP NOT NULL, ALTER COLUMN hourly_rate DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_timesheet CHANGE rate rate DOUBLE PRECISION NOT NULL, CHANGE fixed_rate fixed_rate DOUBLE PRECISION DEFAULT NULL, CHANGE hourly_rate hourly_rate DOUBLE PRECISION DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_activities ALTER COLUMN fixed_rate TYPE NUMERIC(10, 2) USING fixed_rate::NUMERIC(10, 2), ALTER COLUMN fixed_rate DROP NOT NULL, ALTER COLUMN fixed_rate DROP DEFAULT, ALTER COLUMN hourly_rate TYPE NUMERIC(10, 2) USING hourly_rate::NUMERIC(10, 2), ALTER COLUMN hourly_rate DROP NOT NULL, ALTER COLUMN hourly_rate DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_activities CHANGE fixed_rate fixed_rate NUMERIC(10, 2) DEFAULT NULL, CHANGE hourly_rate hourly_rate NUMERIC(10, 2) DEFAULT NULL');
        }
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_customers RENAME COLUMN email TO mail');
            $this->addSql('ALTER TABLE kimai2_customers ALTER COLUMN mail TYPE VARCHAR(255) USING mail::VARCHAR(255), ALTER COLUMN mail DROP NOT NULL, ALTER COLUMN mail DROP DEFAULT, ALTER COLUMN fixed_rate TYPE NUMERIC(10, 2) USING fixed_rate::NUMERIC(10, 2), ALTER COLUMN fixed_rate DROP NOT NULL, ALTER COLUMN fixed_rate DROP DEFAULT, ALTER COLUMN hourly_rate TYPE NUMERIC(10, 2) USING hourly_rate::NUMERIC(10, 2), ALTER COLUMN hourly_rate DROP NOT NULL, ALTER COLUMN hourly_rate DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_customers CHANGE email mail VARCHAR(255) DEFAULT NULL, CHANGE fixed_rate fixed_rate NUMERIC(10, 2) DEFAULT NULL, CHANGE hourly_rate hourly_rate NUMERIC(10, 2) DEFAULT NULL');
        }
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_projects ALTER COLUMN budget TYPE NUMERIC(10, 2) USING budget::NUMERIC(10, 2), ALTER COLUMN budget SET NOT NULL, ALTER COLUMN budget DROP DEFAULT, ALTER COLUMN fixed_rate TYPE NUMERIC(10, 2) USING fixed_rate::NUMERIC(10, 2), ALTER COLUMN fixed_rate DROP NOT NULL, ALTER COLUMN fixed_rate DROP DEFAULT, ALTER COLUMN hourly_rate TYPE NUMERIC(10, 2) USING hourly_rate::NUMERIC(10, 2), ALTER COLUMN hourly_rate DROP NOT NULL, ALTER COLUMN hourly_rate DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_projects CHANGE budget budget NUMERIC(10, 2) NOT NULL, CHANGE fixed_rate fixed_rate NUMERIC(10, 2) DEFAULT NULL, CHANGE hourly_rate hourly_rate NUMERIC(10, 2) DEFAULT NULL');
        }
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_timesheet ALTER COLUMN rate TYPE NUMERIC(10, 2) USING rate::NUMERIC(10, 2), ALTER COLUMN rate SET NOT NULL, ALTER COLUMN rate DROP DEFAULT, ALTER COLUMN fixed_rate TYPE NUMERIC(10, 2) USING fixed_rate::NUMERIC(10, 2), ALTER COLUMN fixed_rate DROP NOT NULL, ALTER COLUMN fixed_rate DROP DEFAULT, ALTER COLUMN hourly_rate TYPE NUMERIC(10, 2) USING hourly_rate::NUMERIC(10, 2), ALTER COLUMN hourly_rate DROP NOT NULL, ALTER COLUMN hourly_rate DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_timesheet CHANGE rate rate NUMERIC(10, 2) NOT NULL, CHANGE fixed_rate fixed_rate NUMERIC(10, 2) DEFAULT NULL, CHANGE hourly_rate hourly_rate NUMERIC(10, 2) DEFAULT NULL');
        }
    }
}
