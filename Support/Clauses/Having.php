<?php

namespace ElegenceIO\Database\Orm\Support\Clauses;

trait Having
{

    public function having(string $key, mixed $value, $operator = null)
    {
        $param = $this->generateParam("having");
        $operator=$operator ?? "=";
        $condition  = "$key $operator :$param";
        $boolean = "AND";
        if (!empty($this->parts["having"])) {
            $condition = " $boolean " . $condition;
        }
        $this->parts["having"][] = $condition;
        $this->bindings[$param] = $value;
        return $this;
    }

    protected function compileHaving()
    {
        if(empty($this->parts["having"])) return " ";
        
        return " HAVING " . implode(" ",$this->parts["having"]);
        
    }
}