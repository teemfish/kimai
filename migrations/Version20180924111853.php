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
 * Changes the invoice templates table for:
 * - shorter template name and proper index name
 * - VAT supporting percentages
 */
final class Version20180924111853 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE kimai2_invoice_templates SET name=SUBSTRING(name, 1, 60)');
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_invoice_templates ALTER COLUMN name TYPE VARCHAR(60) USING name::VARCHAR(60), ALTER COLUMN name SET NOT NULL, ALTER COLUMN name DROP DEFAULT, ALTER COLUMN vat TYPE DOUBLE PRECISION USING vat::DOUBLE PRECISION, ALTER COLUMN vat DROP NOT NULL, ALTER COLUMN vat SET DEFAULT 0');
        } else {
            $this->addSql('ALTER TABLE kimai2_invoice_templates CHANGE name name VARCHAR(60) NOT NULL, CHANGE vat vat DOUBLE PRECISION DEFAULT 0');
        }
    }

    public function down(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_invoice_templates ALTER COLUMN name TYPE VARCHAR(255) USING name::VARCHAR(255), ALTER COLUMN name SET NOT NULL, ALTER COLUMN name DROP DEFAULT, ALTER COLUMN vat TYPE INT USING vat::INT, ALTER COLUMN vat DROP NOT NULL, ALTER COLUMN vat DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_invoice_templates CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE vat vat INT DEFAULT NULL');
        }
    }
}
