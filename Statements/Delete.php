<?php
namespace ElegenceIO\Database\Orm\Statements;

use ElegenceIO\Database\Statements;
use ElegenceIO\Database\Support\Clauses\Where;
use ElegenceIO\Database\Support\Helpers;

final class Delete extends Statements
{
    use Where;
    public function __construct(private string $table, private ?array $data=null)
    {

    }

    protected function  steps() : array {
        return ["compileDelete","compileWhere"];
    }

    protected function compileDelete()
    {

    return "DELETE FROM {$this->table} ";
    }
}