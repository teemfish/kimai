<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Doctrine\Extensions;

use App\Doctrine\Extensions\Year;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Year::class)]
class YearTest extends AbstractDateFunctionTestCase
{
    protected function getFunctionClass(): string
    {
        return Year::class;
    }
}
