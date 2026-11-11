<?php
namespace ElegenceIO\Database\Orm\Statements;

use ElegenceIO\Database\Statements;
final class Update extends Statements
{
    public function __construct(private string $table,private array $data = [])
    {

    }

    protected function steps():array
    {
        return ["compileUpdate"];
    }

    protected function compileUpdate():string
    {
        return '';
    }

}