<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\StringType;

final class CaseInsensitiveStringType extends StringType
{
    public const NAME = 'case_insensitive_string';

    /**
     * @param array<mixed> $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if ($platform instanceof PostgreSQLPlatform) {
            return 'CITEXT';
        }

        return parent::getSQLDeclaration($column, $platform);
    }
}
