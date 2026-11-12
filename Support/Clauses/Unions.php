<?php

/** 
 * Come back to this one.
 */
namespace ElegenceIO\Database\Orm\Support\Clauses;

trait Unions
{

  private function addUnion(string $type,mixed $query)
  {
        $this->parts["unions"][] = ["type"=>$type,
        "query"=> $query,];
        return $this;
  }

  public function union(mixed $sql)
  {
    $this->addUnion("UNION", $sql);
    return $this;
  }

  public function unionAll(mixed $sql)
  {

    $this->addUnion("UNION ALL",$sql);
    return $this;
  }

  protected function compileUnion()
  {
      $sql = [];
    if (empty($this->parts["unions"])) return " ";

      foreach ($this->parts["unions"] as ["type"=> $type,"query"=> $query]) {
       
        $built = $query->compile("select");
        $sql[] = "{$type} {$built["sql"]}";
        $this->bindings = \array_merge($this->bindings,$built["bindings"]);
        }

      //   // Append the SQL
        return implode(" ",$sql);
  }

}
