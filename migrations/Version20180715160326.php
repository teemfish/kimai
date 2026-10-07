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
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration for FOSUserBundle
 *
 * Changes the table structure of "users" table and migrates from json_array type to serialized array,
 * probably also fixing the higher required MariaDB version.
 *
 * This was fixed in earlier migrations for new installations, but it is still in here for users migrating up from a lower version.
 */
final class Version20180715160326 extends AbstractMigration
{
    /**
     * @var Index[]
     */
    protected $indexesOld = [];

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function up(Schema $schema): void
    {
        // delete all existing indexes
        $indexesOld = $schema->getTable('kimai2_users')->getIndexes();
        foreach ($indexesOld as $index) {
            if (\in_array('name', $index->getColumns()) || \in_array('mail', $index->getColumns())) {
                $this->indexesOld[] = $index;
                $this->addSql($this->connection->getDatabasePlatform()->getDropIndexSQL($index->getName(), 'kimai2_users'));
            }
        }

        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_users RENAME COLUMN name TO username');
            $this->addSql('ALTER TABLE kimai2_users RENAME COLUMN mail TO email');
            $this->addSql('ALTER TABLE kimai2_users RENAME COLUMN active TO enabled');
            $this->addSql('ALTER TABLE kimai2_users ALTER COLUMN username TYPE VARCHAR(180) USING username::VARCHAR(180), ALTER COLUMN username SET NOT NULL, ALTER COLUMN username DROP DEFAULT, ADD username_canonical VARCHAR(180) DEFAULT NULL, ALTER COLUMN email TYPE VARCHAR(180) USING email::VARCHAR(180), ALTER COLUMN email SET NOT NULL, ALTER COLUMN email DROP DEFAULT, ADD email_canonical VARCHAR(180) DEFAULT NULL, ADD salt VARCHAR(255) DEFAULT NULL, ADD last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ADD confirmation_token VARCHAR(180) DEFAULT NULL, ADD password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ALTER COLUMN password TYPE VARCHAR(255) USING password::VARCHAR(255), ALTER COLUMN password SET NOT NULL, ALTER COLUMN password DROP DEFAULT, ALTER COLUMN alias TYPE VARCHAR(60) USING alias::VARCHAR(60), ALTER COLUMN alias DROP NOT NULL, ALTER COLUMN alias DROP DEFAULT, ALTER COLUMN registration_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING registration_date::TIMESTAMP(0) WITHOUT TIME ZONE, ALTER COLUMN registration_date DROP NOT NULL, ALTER COLUMN registration_date DROP DEFAULT, ALTER COLUMN title TYPE VARCHAR(50) USING title::VARCHAR(50), ALTER COLUMN title DROP NOT NULL, ALTER COLUMN title DROP DEFAULT, ALTER COLUMN avatar TYPE VARCHAR(255) USING avatar::VARCHAR(255), ALTER COLUMN avatar DROP NOT NULL, ALTER COLUMN avatar DROP DEFAULT, ALTER COLUMN roles TYPE TEXT USING roles::TEXT, ALTER COLUMN roles SET NOT NULL, ALTER COLUMN roles DROP DEFAULT, ALTER COLUMN enabled TYPE BOOLEAN USING enabled::BOOLEAN, ALTER COLUMN enabled SET NOT NULL, ALTER COLUMN enabled DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_users CHANGE name username VARCHAR(180) NOT NULL, ADD username_canonical VARCHAR(180) NOT NULL, CHANGE mail email VARCHAR(180) NOT NULL, ADD email_canonical VARCHAR(180) NOT NULL, ADD salt VARCHAR(255) DEFAULT NULL, ADD last_login DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(180) DEFAULT NULL, ADD password_requested_at DATETIME DEFAULT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE alias alias VARCHAR(60) DEFAULT NULL, CHANGE registration_date registration_date DATETIME DEFAULT NULL, CHANGE title title VARCHAR(50) DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE active enabled TINYINT(1) NOT NULL');
        }
        $this->addSql('UPDATE kimai2_users set username_canonical = username');
        $this->addSql('UPDATE kimai2_users set email_canonical = email');

        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_users ALTER COLUMN username_canonical SET NOT NULL, ALTER COLUMN email_canonical SET NOT NULL');
        }

        $this->addSql('UPDATE kimai2_users SET roles = \'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}\' WHERE roles LIKE \'%ROLE_SUPER_ADMIN%\'');
        $this->addSql('UPDATE kimai2_users SET roles = \'a:1:{i:0;s:10:"ROLE_ADMIN";}\' WHERE roles LIKE \'%ROLE_ADMIN%\'');
        $this->addSql('UPDATE kimai2_users SET roles = \'a:1:{i:0;s:13:"ROLE_TEAMLEAD";}\' WHERE roles LIKE \'%ROLE_TEAMLEAD%\'');
        $this->addSql('UPDATE kimai2_users SET roles = \'a:0:{}\' WHERE roles LIKE \'%ROLE_USER%\'');
        $this->addSql('UPDATE kimai2_users SET roles = \'a:1:{i:0;s:13:"ROLE_CUSTOMER";}\' WHERE roles LIKE \'%ROLE_CUSTOMER%\'');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9AC5BCE92FC23A8 ON kimai2_users (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9AC5BCEA0D96FBF ON kimai2_users (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9AC5BCEC05FB297 ON kimai2_users (confirmation_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9AC5BCEF85E0677 ON kimai2_users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9AC5BCEE7927C74 ON kimai2_users (email)');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function down(Schema $schema): void
    {
        $indexToDelete = ['UNIQ_B9AC5BCE92FC23A8', 'UNIQ_B9AC5BCEA0D96FBF', 'UNIQ_B9AC5BCEC05FB297', 'UNIQ_B9AC5BCEF85E0677', 'UNIQ_B9AC5BCEE7927C74'];
        foreach ($indexToDelete as $indexName) {
            $this->addSql($this->connection->getDatabasePlatform()->getDropIndexSQL($indexName, 'kimai2_users'));
        }

        if ($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            $this->addSql('ALTER TABLE kimai2_users RENAME COLUMN username TO name');
            $this->addSql('ALTER TABLE kimai2_users RENAME COLUMN email TO mail');
            $this->addSql('ALTER TABLE kimai2_users RENAME COLUMN enabled TO active');
            $this->addSql('ALTER TABLE kimai2_users ALTER COLUMN name TYPE VARCHAR(60) USING name::VARCHAR(60), ALTER COLUMN name SET NOT NULL, ALTER COLUMN name DROP DEFAULT, ALTER COLUMN mail TYPE VARCHAR(160) USING mail::VARCHAR(160), ALTER COLUMN mail SET NOT NULL, ALTER COLUMN mail DROP DEFAULT, DROP username_canonical, DROP email_canonical, DROP salt, DROP last_login, DROP confirmation_token, DROP password_requested_at, ALTER COLUMN password TYPE VARCHAR(254) USING password::VARCHAR(254), ALTER COLUMN password DROP NOT NULL, ALTER COLUMN password DROP DEFAULT, ALTER COLUMN roles TYPE TEXT USING roles::TEXT, ALTER COLUMN roles SET NOT NULL, ALTER COLUMN roles DROP DEFAULT, ALTER COLUMN alias TYPE VARCHAR(60) USING alias::VARCHAR(60), ALTER COLUMN alias DROP NOT NULL, ALTER COLUMN alias DROP DEFAULT, ALTER COLUMN registration_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING registration_date::TIMESTAMP(0) WITHOUT TIME ZONE, ALTER COLUMN registration_date DROP NOT NULL, ALTER COLUMN registration_date DROP DEFAULT, ALTER COLUMN title TYPE VARCHAR(50) USING title::VARCHAR(50), ALTER COLUMN title DROP NOT NULL, ALTER COLUMN title DROP DEFAULT, ALTER COLUMN avatar TYPE VARCHAR(255) USING avatar::VARCHAR(255), ALTER COLUMN avatar DROP NOT NULL, ALTER COLUMN avatar DROP DEFAULT, ALTER COLUMN active TYPE BOOLEAN USING active::BOOLEAN, ALTER COLUMN active SET NOT NULL, ALTER COLUMN active DROP DEFAULT');
        } else {
            $this->addSql('ALTER TABLE kimai2_users CHANGE username name VARCHAR(60) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE email mail VARCHAR(160) NOT NULL COLLATE utf8mb4_unicode_ci, DROP username_canonical, DROP email_canonical, DROP salt, DROP last_login, DROP confirmation_token, DROP password_requested_at, CHANGE password password VARCHAR(254) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE alias alias VARCHAR(60) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE registration_date registration_date DATETIME DEFAULT NULL, CHANGE title title VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE enabled active TINYINT(1) NOT NULL');
        }

        $this->addSql('UPDATE kimai2_users SET roles = \'["ROLE_SUPER_ADMIN"]\' WHERE roles LIKE \'%ROLE_SUPER_ADMIN%\'');
        $this->addSql('UPDATE kimai2_users SET roles = \'["ROLE_ADMIN"]\' WHERE roles LIKE \'%ROLE_ADMIN%\'');
        $this->addSql('UPDATE kimai2_users SET roles = \'["ROLE_TEAMLEAD"]\' WHERE roles LIKE \'%ROLE_TEAMLEAD%\'');
        $this->addSql('UPDATE kimai2_users SET roles = \'["ROLE_USER"]\' WHERE roles LIKE \'%ROLE_USER%\'');
        $this->addSql('UPDATE kimai2_users SET roles = \'["ROLE_CUSTOMER"]\' WHERE roles LIKE \'%ROLE_CUSTOMER%\'');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9AC5BCE5E237E06 ON kimai2_users (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9AC5BCE5126AC48 ON kimai2_users (mail)');

        $usersTable = $schema->getTable('kimai2_users');
        foreach ($this->indexesOld as $index) {
            $usersTable->addIndex($index->getColumns(), $index->getName(), $index->getFlags(), $index->getOptions());
        }
    }
}
