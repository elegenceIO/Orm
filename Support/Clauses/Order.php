<?php

namespace ElegenceIO\Database\Orm\Support\Clauses;

use Exception;

trait Order
{
    private string $direction = "";
    private function setDirection(string $direction):string
    {
        $direction = strtoUpper($direction);

        if(!in_array($direction, ['ASC', 'DESC']))
        {
            throw new Exception("Parameter Value is not supported ");
        }

        return $direction;
    }

    public function orderBy(string $direction,  ...$values)
    {

        $args = implode(", ",$values);
        $this->parts["order"]= " ORDER BY  $args ". $this->setDirection($direction)." ";
        return $this;
    }

    public function compileOrder()
    {
        if(empty($this->parts["order"])) return ' ';
        // echo $this->order;
        return $this->parts["order"];
    }
}