<?php
namespace ProtoMapper\Binds;
class ProtocolBind
{
    const DEFAULT_TYPE = 'string';
		
    protected $name;
    protected $source;
    protected $target;
    /**
     *
     * @var IValueParser $parser 
     */
    protected $parser;
    protected $type;
    /**
     *
     * @var boolean 
     */
    protected $default;
    protected $isMake;
    protected $isUnique;
    /**
     *
     * @var ProtocolBind[]
     */
    protected $bindings;
    
    protected static $bind_cache = array();
    
    /**
     * Ctor
     * @param string $source
     * @param string $target
     * @param string $type
     * @param string $name Name of the bind. Defaults to $source when not specified
     * @param string $default default value when the parsing and no entries are found
     * @param string $parser a parser responsible for creating the object based on the content found
     * $param bool $make determines whether the bind should create the object even if not found
     * @param ProtocolBind[] $bindings sub bindings that will dictate assignment
     */
    public function __construct($source, $target, $type = null, $name = null, $unique = false, $default = null, $parser = null, $make = false, $bindings = array()) {
        $this->source = $source;
        $this->target = $target;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        if(!empty($type)){
            $this->type = $type;
        }
		else{
			$this->type = self::DEFAULT_TYPE;
		}
        if(empty($name)){
            $this->name = $source;
        }
        else $this->name = $name;
        if(!empty($default)){
            $this->default = cast($default, $this->type);
        }
        if(!empty($make)){
            $this->isMake = cast($make, "bool");
        }
        if(!empty($make)){
            $this->isUnique = cast($unique, "bool");
        }
        $this->bindings = array();
        foreach($bindings as $binding){
            $this->bindings[$binding->name()] = $binding;
        }
    }
    
    
    protected static function from_cache($object){
        if(isset(self::$bind_cache[get_class($object)])){
            $items = self::$bind_cache[get_class($object)];
            foreach ($items as $item){
                if($object == $item){
                    return $item;
                }
            }
        }
        else{
            self::$bind_cache[get_class($object)] = array();
            return self::$bind_cache[get_class($object)][] = $object;
        }
    }


    /**
     * 
     * @param mixed $content
     * @param IValueParser $callback
     * @return mixed
     */
    public function parse($content, $callback = null)
    {
        $result = null;
        if(isset($content) || $this->isMake()){
            if(!empty($this->parser)){
                $parser = new $this->parser($this->bindings(), $this->type());  //create a new parser with the given bindings
                $result = $parser->parse($content, $callback);                  //pass the contents through the parser to get results
            }
            elseif(!empty($this->bindings)){
                if(isset($this->type) && !is_scalar_type($this->type)){
                    //call 'new' for non-scalar types and create the instances
                    $result = new $this->type;
                    foreach($this->bindings() as $binding){
                        if(isset($callback)){
                            $newcontent = $callback->getValue($content, $binding->source());
                            $result = set_value($result, $binding->target(), $binding->parse($newcontent, $callback));
                        }
                        else{
                            $result = set_value($result, $binding->target(), $binding->parse($newcontent));
                        }
                    }
                }
            }
            elseif(is_array($content)){
                $result = cast($content[0], $this->type());
            }
            else{
                $result = cast($content, $this->type());
            }
        }
        elseif(isset($this->default)){
            $result = $this->default;
        }
        if($this->isUnique()){
            $result = self::from_cache($result);
        }
        return $result;
    }
    
    public function name()
    {
        return $this->name;
    }
    
    public function source()
    {
        return $this->source;
    }
    
    public function type()
    {
        return $this->type;
    }
    
    public function target()
    {
        return $this->target;
    }
	
	public function parser()
    {
        return $this->parser;
    }
    
    public function defaultValue()
    {
        return $this->default;
    }
    
    public function isMake()
    {
        return $this->isMake;
    }
    
    public function isUnique()
    {
        return $this->isUnique;
    }
    
    public function bindings($index = null)
    {
        $bindings = array_values($this->bindings);
        if(isset($index)){
            return $bindings[$index];
        }
        return $bindings;
    }
}
