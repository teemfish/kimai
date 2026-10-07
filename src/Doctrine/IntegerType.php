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
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType as BaseIntegerType;
use Doctrine\DBAL\Types\Types;

final class IntegerType extends BaseIntegerType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value !== null && $platform instanceof PostgreSQLPlatform) {
            $integer = \is_bool($value) ? (int) $value : (\is_string($value) && is_numeric($value) ? (float) $value : $value);
            if (filter_var($integer, FILTER_VALIDATE_INT, ['options' => ['min_range' => -2147483648, 'max_range' => 2147483647]]) === false) {
                throw ConversionException::conversionFailed($value, Types::INTEGER);
            }
        }

        return parent::convertToDatabaseValue($value, $platform);
    }
}
