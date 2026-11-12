<?php
namespace ElegenceIO\Database\Orm\Support\Clauses;

trait Limit
{
    // Set limit parts
    private function limitOffset(int $offset): string
    {
        $param = $this->generateParam("limitOffset");
        $this->bindings[$param] = $offset;
        return ":$param";
    }

    private function limitCount(int $count): string
    {
        $param = $this->generateParam("limitCount");
        $this->bindings[$param] = $count;
        return ":$param";
    }

    private function setLimit(int $count, ?int $offset = null): void
{
    if ($offset !== null) {
        // MySQL: LIMIT offset, count
        $limitSql = $this->limitOffset($offset) . ", " . $this->limitCount($count);
    } else {
        $limitSql = $this->limitCount($count);
    }

    $this->parts['limit'] = ["LIMIT $limitSql"];
}


    public function limit(int $count, ?int $offset = null):self
    {
        $this->setLimit($count, $offset);
        return $this;
    }



    protected function compileLimit():string
    {
        if (empty($this->parts['limit'])) return ' ';
        return " " . implode(" ", $this->parts['limit']);
        
    }
}