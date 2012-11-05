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
     * @var $type 
     */
    protected $default;
    /**
     *
     * @var ProtocolBind[]
     */
    protected $bindings;
    
    /**
     * Ctor
     * @param string $source
     * @param string $target
     * @param string $type
     * @param string $name Name of the bind. Defaults to $source when not specified
     * @param string $default default value when the parsing and no entries are found
     * @param string $parser
     * @param ProtocolBind[] $bindings
     */
    public function __construct($source, $target, $type = null, $name = null, $default = null, $parser = null, $bindings = array()) {
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
        $this->bindings = array();
        foreach($bindings as $binding){
            $this->bindings[$binding->source] = $binding;
        }
    }
    
    /**
     * 
     * @param mixed $content
     * @param IValueParser $callback
     * @return mixed
     */
    public function parse($content, $callback)
    {
        $result = null;
        if(isset($content)){
            if(!empty($this->parser)){
                $parser = new $this->parser($this->bindings(), $this->type());  //create a new parser with the given bindings
                $result = $parser->parse($content, $callback);                  //pass the contents through the parser to get results
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
    
    public function bindings()
    {
        return array_values($this->bindings);
    }
}
