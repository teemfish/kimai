<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Doctrine\Extensions;

use App\Doctrine\Extensions\Day;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Day::class)]
class DayTest extends AbstractDateFunctionTestCase
{
    protected function getFunctionClass(): string
    {
        return Day::class;
    }
}
