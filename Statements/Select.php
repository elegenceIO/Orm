<?php
namespace ElegenceIO\Database\Orm\Statements;

use ElegenceIO\Database\Statements;
use ElegenceIO\Database\Orm\Support\Clauses\Where;
use ElegenceIO\Database\Orm\Support\Clauses\Grouping;
use ElegenceIO\Database\Orm\Support\Clauses\Having;
use ElegenceIO\Database\Orm\Support\Clauses\Joins;
use ElegenceIO\Database\Orm\Support\Clauses\Limit;
use ElegenceIO\Database\Orm\Support\Clauses\Order;
use ElegenceIO\Database\Orm\Support\Clauses\Unions;
use ElegenceIO\Database\Support\CoreHelper;

class Select extends Statements
{
    use CoreHelper;
    protected array $parts = [];
    protected array $bindings = [];
    protected  array $columns = [];
    protected  array $aggregates = [];
    protected ?string $alias = null;

    //Shared Helpers
    // Shared Traits
     use Where, Unions, Joins, Limit, Having, Grouping, Order;

    public function __construct(protected string $table)
    {
        $this->table = $table;
    }

    protected function steps():array
    {
        // Write Array In order of Statement
        return ["compileSelect","compileJoins","compileWhere","compileGroups","compileHaving","compileUnion","compileOrder","compileLimit"];
    }

    public function as(string $alias):static
    {
      $this->alias = $alias;
      return $this;
    }

    public function columns(string $value):static
    {
        $this->columns[] = $value;
        return $this;
    }

    public function compileSelect()
    {

            $cols = $this->columns ? implode(', ', $this->columns) : '*';   // empty means *
            $from = $this->table . ($this->alias ? " AS {$this->alias}" : '');
            return "SELECT $cols FROM $from";
    }





}