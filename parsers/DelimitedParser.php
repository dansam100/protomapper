<?php
namespace ProtoMapper\Parsers;;
/**
 * Description of DelimitedParser
 *
 * @author sam.jr
 */
class DelimitedParser
{
    protected $delimiter;
    protected $mappings;
    protected $content;
    protected $type;
    private $result;
    private $regex;
    /**
     * 
     * @param ProtocolBind[] $mappings
     * @param string $delimiter
     */
    public function __construct($mappings, $type, $delimiter = null) {
        $this->delimiter = sprintf('/[%s]+/', $delimiter);
        $this->mappings = $mappings;
        $this->type = $type;
        $this->result = array();
    }
    
    public function parse($content, $callback = null)
    {
        $results = array();
        if(!empty($this->mappings)){
            foreach($this->mappings as $mapping){
                $target = $mapping->target();
                $value = $content;
                if(isset($callback)){
                    $value = $callback->getValue($content, $mapping->source());
                }
                $splits = preg_split($this->delimiter, $mapping->parse($value, $callback));
                $results = array_map("trim", $splits);
            }
        }
        else{
            $results = array_map("trim", preg_split($this->delimiter, $content));
        }
        foreach($results as $result){
            if(!empty($result)){
                //call 'new' for non-scalar types and create the instances
                if(!\is_scalar_type($this->type)){
                    $item = set_value(new $this->type, $target, $result);
                    $this->results[] = $item;
                }
                else{   //return an array of objects for the scalar types
                    $this->results[] = cast($result, $this->type);
                }
            }
        }
        return $this->results;
    }
}