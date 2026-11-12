<?php

namespace ElegenceIO\Database\Orm\Support\Clauses;
use Exception;

trait Where
{

    /**
     * /ElgegenceIO\Database\Queries\Clauses\Where
     * Version 1.0.0
     * Requires @param $bindings
     */

    protected function addCondition(string $condition, string $boolean = "AND")
    {
        if (!empty($this->parts["where"])) {
            $condition = " $boolean " . $condition;
        }

        $this->parts["where"][] = $condition;
    }

    
    // Start Where()
    public function where(string $key, mixed $value, mixed $operator = null)
    {
        $operator = $operator ?? "=";
        $param = $this->generateParam("where");
        $condition = "$key $operator :$param";
        $this->addCondition($condition, "AND");
        $this->bindings[$param] = $value;
        return $this;
    }

    //    Allow Sql to Pass OR to the Where Statement
    public function orWhere(string $key, mixed $value, mixed $operator = null)
    {
        $operator = $operator ?? "=";
        $param = $this->generateParam("where");
        $condition = "$key $operator :$param";
        $this->addCondition($condition, "OR");
        $this->bindings[$param] = $value;
        return $this;
    }
    
    /**
     * IN clause
     */
    public function in(string $key, array $values, string $boolean = "AND")
    {
        if (empty($values)) {
            throw new \InvalidArgumentException("Values array for IN() cannot be empty.");
        }

        $placeholders = [];
        foreach ($values as $value) {
            $param = $this->generateParam("in");
            $placeholders[] = ":$param";
            $this->bindings[$param] = $value;
        }

        $condition = "$key IN (" . implode(", ", $placeholders) . ")";
        $this->addCondition($condition, $boolean);

        return $this;
    }

    //       Reverse to In
    //Display ALl results that will show results other than those called

    public function notIn(string $key, array $values, string $boolean = "AND")
    {
        if (empty($values)) {
            throw new \InvalidArgumentException("Values array for NOT IN() cannot be empty.");
        }

        $placeholders = [];
        foreach ($values as $value) {
            $param = $this->generateParam("notin_");
            $placeholders[] = ":$param";
            $this->bindings[$param] = $value;
        }

        $condition = "$key NOT IN (" . implode(", ", $placeholders) . ")";
        $this->addCondition($condition, $boolean);

        return $this;
    }


    public function like(string $key, mixed $value)
    {
        $param = $this->generateParam("like");
        $condition = "$key LIKE :$param";
        $this->addCondition($condition, "AND");
        $this->bindings[$param] = $value;
        return $this;
    }

    public  function orLike(string $key, mixed $value)
    {
        $param = $this->generateParam("like");
        $condition = "$key LIKE :$param";
        $this->addCondition($condition, "OR");
        $this->bindings[$param] = $value;
        return $this;
    }


    public  function notLike(string $key, mixed $value)
    {
        $param = $this->generateParam("notLike_");
        $condition = "$key NOT LIKE :$param";
        $this->addCondition($condition, "AND");
        $this->bindings[$param] = $value;
        return $this;
    }

    public  function orNotLike(string $key, mixed $value)
    {
        $param = $this->generateParam("notLike_");
        $condition = "$key NOT LIKE :$param";
        $this->addCondition($condition, "OR");
        $this->bindings[$param] = $value;
        return $this;
    }

    public  function  between(string $key, int $value, int $value2)
    {
        $param = $this->generateParam("between");
        $param2 = $this->generateParam("between");
        $condition = "$key BETWEEN :$param AND :$param2";
        $this->addCondition($condition, "AND");
        $this->bindings[$param] = $value;
        $this->bindings[$param2] = $value2;
        return $this;
    }

    public  function  orBetween(string $key, int $value, int $value2)
    {
        $param = $this->generateParam("between");
        $param2 = $this->generateParam("between");
        $condition = "$key BETWEEN :$param AND :$param2";
        $this->addCondition($condition, "OR");
        $this->bindings[$param] = $value;
        $this->bindings[$param2] = $value2;
        return $this;
    }



    public function compileWhere()
    {
        // return nothing if calue is empty
        if(empty($this->parts["where"])) return '';
        // Return if Value is not empty;
        return " WHERE " .implode(" ", $this->parts["where"]);
        

    }

    // End WHere
}
