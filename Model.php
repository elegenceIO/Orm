<?php

namespace ElegenceIO\Database\Orm;

// Add Path as a fall back
use ElegenceIO\Database\Orm\Builder;

/**
 * Abstraction Class
 * avoid using constructor.
 */
abstract class Model
{

    /**
     | Default Property value Builder()
     * @property ?Builder $builder
     * @return null
     */

    private ?Builder $builder = null;

    /**
     | Attribute are used when creating an Active Record request
     * @param array $attributes
     * @return array
     */
     protected array $attributes = [];

    /**
     | fillable are used for limiting values when creating an Active Record request
     * @param array $fillable
     * @return array
     */
 protected array $fillable = [];

    /**
     | Gaurds are used to prevent values when creating an Active Record request
     * @param array $gaurds
     * @return array
     */
    protected array $guards = [];
    
    /**
     | check if exists  are used when creating an Active Record request
     * @param array $exists
     * @return array
     */
    public bool $exists = false;

    /**
     | Obtain table name of called class model;
     | Potentially remove if in place of passInstance();
     * @param string $table
     * @return array
     */
    protected string $table =  "";
    /**
     | Parameter $instance used to set and obtain the called model aka called_class() : static::class;
     * @param array $instance
     * @return string|null
     */

    protected ?string $instance = null;

    //this may get removed
    protected string $namespace = "";


    // Obtain and Pass Atributes;
    
    private function passAttributes():array
    {
        if(empty($this->attributes)) return [];
        return $this->attributes;
    }

    private function passGaurds():array
    {   
        if(empty($this->guards)) return [];
        return $this->guards;
    }


    private function passFillable():array 
    {
        if(empty($this->fillable)) return [];
        return $this->fillable;
    }

    private function ifExists():bool
    {
        return $this->exists ? true : false;
    }

    /**
     * @method __set(string $key, mixed $value);
     * @param string $key
     * @param mixed $value
     * @see array 
     * @property $this->attributes
     * @return void
     * 
     */
    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @method __get(string $key, mixed $value);
     * @param string $key
     * @see array $this->attributes
     * @return string|null
     */
    public function __get(string $key): string|null
    {
        return $this->attributes[$key] ?? null;
    }
    
    
    /**
     * 
     | static method for accessing __call() method in a non active record way.
     | ie: Users::where("username",1)->get();
     * 
     * @method static __callStatic(string $method,array $params)
     * @see __call(string $method,array $params)
     * @param string $method
     * @param mixed $method
     */
    public static function __callStatic(string $method, array $params)
    {
        $instance = new static();
        $instance->instance = static::class;
        return $instance->__call($method, $params);
    }

    /**
    | Make Model Calls located at App/Http/Models;
    |
    |
    * @method static __call(string $method,array $params)
    * @param string $method
    * @param mixed $method
    */

    public function __call(string $method, array $params)
    {
        // Get the Called Instance
        $instance = $this->instance ?? \get_called_class();
        $builder = $this->build($instance);

        // check for new attributes;

        if(!empty($this->attributes && in_array($method,["insert","update"])))
        {
            if(empty($params))
            {
                $params = $this->attributes;
            }
        }
        return $builder->$method(...$params);
    }

    /**
     * Private method Build Class
     * This class Opens up the Builder
     */
    private function Build(string $instance) {

        $builder = new Builder($instance,app("connection"));
        
         if(!empty($this->attributes))
        {
            $builder->setAttributes($this->attributes);
        }

        // Check if there are any required Fillable Values
        if (!empty($this->fillable)) {
            $builder->fillable($this->fillable);
        }

        //check if there are any SafeGaurds in place.
        if (!empty($this->guards)) {
            $builder->guarded($this->guards);
        }
    

        return $builder;
    }
}
