<?php
namespace ElegenceIO\Database\Orm\Support\Clauses;

trait Grouping
{
    public function groupBy(array|string...$columns)
    {
    // Add the column directly to the group array.
        if(!empty($columns))
        {
            foreach($columns as $column)
            {
                if(is_array($column))
                {
                    foreach($column as $col)
                    {
                        $this->parts["groups"][]  = $col;

                    }
                }
                else
                {
                    $this->parts["groups"][]  = $column;
                }
              
            }
        }
    return $this;
    }

    public function compileGroups()
    {
        if (empty($this->parts["groups"])) return ' ';
            // Ensure the GROUP BY clause is added to the SQL query
        return " GROUP BY " . implode(", ", $this->parts["groups"]);
        
    }
}
