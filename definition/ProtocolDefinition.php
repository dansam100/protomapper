<?php
namespace ProtoMapper\Definition;
use ProtoMapper\Parsers\IValueParser as IValueParser;
/**
 * Description of Protocol
 *
 * @author sam.jr
 */
class ProtocolDefinition implements IValueParser
{
    protected $name;
    protected $type;
    protected $query;
    protected $scope;
    protected $contenttype;
    protected $objects;
    protected $mappings;
    protected $parser = 'ProtoMapper\Parsers\XMLSimpleParser';
    
    /**
     *
     * @param string $name the source
     * @param string $type the target 
     * @param string $contenttype The content type for the protocol
     * @param string $scope the scope to perform operations on
     * @param string $query the query fields to pass into the request
     * @param ProtocolObject[] $objects the objects to create
     * @param ProtocolMapping[] $mappings the mapping assocations related to the protocol
     * @param IParser $parser The parser to use for reading data contents
     */
    public function __construct($name, $type, $contenttype, $scope = null, $query = null, $objects = null, $mappings = null, $parser = null) {
        $this->type = $type;
        $this->name = $name;
        $this->scope = $scope;
        $this->query = $query;
        $this->contenttype = $contenttype;
        if(!empty($parser)){
            $this->parser = $parser;
        }
        $this->mappings = array();
        foreach($mappings as $mapping){
            //$mapping->parent($this);
            $this->mappings[$mapping->name()] = $mapping;
        }
        $this->objects = array();
        foreach($objects as $object){
            $object->parent($this);
            $this->objects[$object->name()] = $object;
        }
    }
    
    /**
     * Get a ProtocolMapping related by name
     * @param string $name
     * @return ProtocolMapping The resulting mapping 
     */
    public function getMappingByName($name)
    {
        if(!empty($this->mappings[$name])){
            return $this->mappings[$name];
        }
        return null;
    }
    
    /**
     * Gets a given ProtocolObject by name
     * @param string $name
     * @return ProtocolMapping The resulting mapping 
     */
    public function getObject($name)
    {
        if(!empty($this->objects[$name])){
            return $this->objects[$name];
        }
        return null;
    }
    
    /**
     * Parses xml data containing relevant information
     * @param string $data the xml data
     * @return \Rexume\Models\Entity The results of the parse operation
     */
    public function parseOne($data)
    {
        $results = $this->parse($data);
        //return the first item in the list
        return (count($results) > 0) ? $results[0] : null;
    }
    
    /**
     * Parses xml data containing relevant information
     * @param string $data the xml data
     * @return \Rexume\Models\Entity[] The results of the parse operation
     */
    public function parse($data)
    {
        $parser_name = $this->parser;
        $parser = new $parser_name($this->objects, $this->type);
        return $parser->parse($data, $this);
    }
    
    /**
     * Constructs a string based query from a list of tokens
     * @param string $query
     * @param array $tokens
     * @return string
     */
    public function createQueryFromTokens($query, $tokens)
    {
        foreach($tokens as $tokenKey => $tokenValue){
            $query = preg_replace($tokenKey, $tokenValue, $query);
        }
        return $query;
    }
    
    /**
     * 
     * @return string protocol request query string
     */
    public function query()
    {
        return $this->query;
    }
    
    public function name()
    {
        return $this->name;
    }
    
    public function type()
    {
        return $this->type;
    }
    
    public function contentType()
    {
        return $this->contenttype;
    }
    
    public function mappings()
    {
        return $this->mappings;
    }
    
    public function scope()
    {
        return $this->scope;
    }
    
    public function sources()
    {
        return array_keys($this->objects);
    }
    
    public function targets()
    {
        return array_values($this->objects);
    }
}