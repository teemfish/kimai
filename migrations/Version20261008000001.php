<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use App\Doctrine\AbstractMigration;
use App\Doctrine\CaseInsensitiveStringType;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

final class Version20261008000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Preserve case-insensitive PostgreSQL account identities';
    }

    public function up(Schema $schema): void
    {
        if (!($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform)) {
            $this->preventEmptyMigrationWarning();

            return;
        }

        $this->addSql('CREATE EXTENSION IF NOT EXISTS citext');
        $this->addSql('ALTER TABLE kimai2_users ADD CONSTRAINT kimai_username_length CHECK (CHAR_LENGTH(username) <= 180), ADD CONSTRAINT kimai_email_length CHECK (CHAR_LENGTH(email) <= 180)');
        $users = $schema->getTable('kimai2_users');
        $users->getColumn('username')->setType(Type::getType(CaseInsensitiveStringType::NAME));
        $users->getColumn('email')->setType(Type::getType(CaseInsensitiveStringType::NAME));
    }

    public function down(Schema $schema): void
    {
        if (!($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform)) {
            $this->preventEmptyMigrationWarning();

            return;
        }

        $this->addSql('ALTER TABLE kimai2_users DROP CONSTRAINT kimai_username_length, DROP CONSTRAINT kimai_email_length');
        $users = $schema->getTable('kimai2_users');
        $users->getColumn('username')->setType(Type::getType(Types::STRING));
        $users->getColumn('username')->setLength(180);
        $users->getColumn('email')->setType(Type::getType(Types::STRING));
        $users->getColumn('email')->setLength(180);
    }

    public function isTransactional(): bool
    {
        return true;
    }
}
