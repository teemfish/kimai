<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine\Extensions;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

final class Month extends FunctionNode
{
    private Node|string $value;

    public function getSql(SqlWalker $sqlWalker): string
    {
        $value = $sqlWalker->walkArithmeticPrimary($this->value);

        if ($sqlWalker->getConnection()->getDatabasePlatform() instanceof PostgreSQLPlatform) {
            return 'EXTRACT(MONTH FROM ' . $value . ')';
        }

        return 'MONTH(' . $value . ')';
    }

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->value = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
