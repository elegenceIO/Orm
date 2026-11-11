<?php
namespace ElegenceIO\Database\Orm\Statements;

use ElegenceIO\Database\Statements;
use ElegenceIO\Database\Support\Statements\Statement;
use ElegenceIO\QueryBuilder\Traits\Helpers;
use Exception;

class Insert  extends Statements
{
    public function __construct(private string $table,private ?array $data=null)
    {
    }

    protected function steps():array
    {
        return ["compileInsert"];
    }
    
    protected function parseData()
    {
        if(empty($this->data))
        {
            throw new Exception("No Data Inputted");
        }

        $columns = [];
        $placeholders = [];
        
        foreach($this->data as $column => $value)
        {
            $param = $this->generateParam("insert");
            $columns[] = $column;
            $placeholders[] = ":$param";
            $this->bindings[$param] = $value;
        }
        return ["columns"=>\implode(",",$columns)
        ,"bindings"=>\implode(",",$placeholders)];

    }

    protected function compileInsert()
    {
        $parts = $this->parseData();
        return "INSERT INTO {$this->table} ({$parts['columns']}) VALUES({$parts['bindings']}) ";
    }
}