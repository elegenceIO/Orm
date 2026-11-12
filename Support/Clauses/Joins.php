<?php

namespace ElegenceIO\Database\Orm\Support\Clauses;

trait Joins
{

private function joinAlias(string $table):string
{
    $parts = explode("_",$table);
    // Reset Alias
    $alias = "";
        foreach($parts as $p)
        {
            $alias .= substr($p,0,1);
        }
         return " $table AS  ". strtolower($alias)." "; 
    }

    private function generateJoin(string $type,string $table,string $parent,string $child):void
    {
        $this->parts["joins"][] = " {$type} ".$this->joinAlias($table) ."ON {$parent}={$child}";
    }

    // Renamed innerjoin to join for consistency

     /**
     * @method join()
     * @param string $table : table name to join
     * @param string $key : key is the name of the table/alias, this is passed to @method on 
     * @return static
     */
    public  function join(string $table,string $key, string|int $child):static
    {
        $this->generateJoin("JOIN",$table,$key,$child);
        return $this;
    }


     /**
     * @method rightJoin()
     * @param string $table : table name to join
     * @param string $key : key is the name of the table/alias, this is passed to @method on 
     * @return static
     */
    public  function rightJoin(string $table,string $key, string|int $child):static
    {
        $this->generateJoin("RIGHT JOIN",$table,$key,$child);
        return $this;
    }

     /**
     * @method crossJoin()
     * @param string $table : table name to join
     * @param string $key : key is the name of the table/alias, this is passed to @method on 
     * @return static
     */
    public function crossJoin(string $table,string $key, string|int $child):static
    {
        $this->generateJoin("CROSS JOIN",$table,$key,$child);
        return $this;
    }

    /**
     * @method leftJoin()
     * @param string $table : table name to join
     * @param string $key : key is the name of the table/alias, this is passed to @method on 
     * @return static
     */
    public  function leftJoin(string $table,string $key, string|int $child):static
    {
        $this->generateJoin("LEFT JOIN",$table,$key,$child);
        return $this;
    }

    /**
     *  @method fullJoin()
     *  @param string $table : table name to join
     *  @param string $key : key is the name of the table/alias, this is passed to @method on
     *  @param string|int $value : value of the join clause
     *  @param string|null $alias : alias for the table
     */
    public  function fullJoin(string $table,string $key, string|int $child):self
    {
        $this->generateJoin("FULL JOIN",$table,$key,$child);
        return $this;
    }

    public function compileJoins():string
    {
        if(empty($this->parts["joins"])) return ' '; 
        return implode(" ", $this->parts["joins"]);
        
    }
}