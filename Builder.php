<?php
namespace ElegenceIO\Database\Orm;

use ElegenceIO\Database\Queries\Database;
use ElegenceIO\Database\Executor;
use ElegenceIO\Database\Gateway;
use ElegenceIO\Database\Interceptor;
use ElegenceIO\Database\Queries\DbQueries;
use ElegenceIO\Database\Support\CoreHelper;
use ElegenceIO\Database\Support\SharedResources;
use Exception;
use PDO;
use PDOException;

class Builder
{
    // Setup Allowed Params;
    private bool $isRaw = false;
    private ?string $sql = null;
    private array $params = [];
    private array $calls = [];
    private ?Gateway $gateway = null;
    private ?DbQueries $database = null;
    use CoreHelper;
    use SharedResources;

    public function __construct(private string $table,private object $connection)
    {
        $this->gateway = new Gateway($connection);
    }

    private function table():string
    {
        return $this->onTable($this->table);
    }


    public function __call(string $method, array $args)
    {
        return $this->record($method,$args);
    }

    protected function record(string $method, array $args): static
    {
        $this->calls[] = [$method, $args];
        return $this;
    }

    /**
     * Crud Attributes for Object Orientated Builder.
     * @see get() // Fetches all values as an array
     * @see first() // Sets limit to 1 and calls @method get()
     * @see insert() // inserts data into tables
     * @see update(); // Updated records within table
     * @see delete() // Deletes Data from table
     */


    /**
     * @method get()
     * @return array
     */
    public function get():array
    {
        $part = $this->run("select");
        $results = $this->gateway->parse($part["sql"],$part["bindings"]);
        return $results->fetchAll(PDO::FETCH_OBJ);
        
    }
    

    /**
     * @method first()
     * @see get();
     * @see $this->limit(1)
     */
    public function first()
    {
        $this->limit(1);

        $results = $this->get()[0];
        if(!$results)
        {
            throw new Exception("No Row Found");
        }

        return $results;
    }



    public function update(array $data=[])
    {
        $part = $this->run("update",$data);
        $result = $this->gateway->parse($part["sql"],$part["bindings"]);
        return $result;
    }


    public function delete(?array $data =[])
    {

        $part = $this->run("delete");
        $results = $this->gateway->parse($part["sql"],$part["bindings"]);
        if(!$results->rowCount())
        {
            throw new PDOException("Record Cannot be found");
        }

        return $results;
    }

    public function insert(?array $data=[])
    {
        $part = $this->run("insert",$data);
        $results = $this->gateway->parse($part["sql"],$part["bindings"]);
        return $results;
    }
    

    // Build the Compiler
    private function run(string $verb,?array $data=[])
    {
        $interceptor = new Interceptor($this->calls,$this->table());
        return $interceptor->compile("orm",$verb,$data);
    }


    
}