<?php
namespace ProtoMapper\Parsers;
/**
 * Description of XMLSimpleParser
 *
 * @author sam.jr
 */
class XMLSimpleParser extends Parser
{
    /**
     *
     * @var mixed results of the parse operation
     */
    private $results;
    /**
     *
     * @var SimpleXMLElement
     */
    private $content;
    /**
     * Ctor
     * @param ProtocolObject[] $mappings 
     */
    public function __construct($mappings, $type) {
        parent::__construct($mappings, $type);
        $this->results = array();
    }
    
    /**
     * Parses a given xml node data using the provided callback as reader
     * @param \SimpleXMLElement $data
     * @param IValueParser $callback A callback for intepreting parsed keys
     * @return Entity[] the parse results
     */
    public function parse($data, $callback)
    {
        $parser = new \SimpleXMLIterator($data);
        $this->content = $data;
        //process the root node
        if(isset($callback))
        {
            foreach($this->mappings as $mapping){
                if(strcmp($mapping->name(), $parser->getName()) == 0){
                    $result = $this->invokeParser($mapping, $parser);
                    if(isset($result)){
                        $this->results[] = $result;
                    }
                }
            }
        }
        //process children
        $this->start($parser, $callback);
        return $this->results;
    }

    
    /**
     * Invokes the parse on a given node
     * @param ProtocolObject $mapping
     * @param \SimpleXMLElement $content
     */
    private function invokeParser($mapping, $content)
    {
        $this->results[] = $mapping->parse($content, $this);
    }
    
    /**
     * Loops through the xml iterator and parses the content
     * @param \SimpleXMLIterator $node
     * @param IValueParser $callback
     */
    private function start($node, $callback)
    {
        for($node->rewind(); $node->valid(); $node->next())
        {
            if(isset($callback))
            {
                $mapping = $callback->getObject($node->key());
                if(!empty($mapping))
                {
                    $result = $this->invokeParser($mapping, $node->current());
                    if(isset($result)){
                        $this->results[] = $result;
                    }
                }
            }
            if($node->hasChildren())
            {
                $this->start($node->current(), $callback);
            }
        }
    }
    
    /**
     * Get the value of the given source binding from the content xml
     * @param \SimpleXMLElement $content the xml to get the value from
     * @param string $source the binding target name
     * @return mixed results of the bind
     */
    public function getValue($content, $key)
    {
        $result =  $content->xpath($key);
        if(!empty($result)){
            if(is_collection($result)){
                $result = $result[0];
            }
            return $result;
        }
        return null;
    }
    
    /**
     * Get the values of the given source binding from the content xml
     * @param \SimpleXMLElement $content the xml to get the value from
     * @param string $source the binding target name
     * @return mixed results of the bind
     */
    public function getValues($content, $key)
    {
        $result =  $content->xpath($key);
        if(!empty($result)){
            return $result;
        }
        return null;
    }
    
    /**
     * 
     * @param string $key
     * @return type
     */
    public function getObject($key) {
        return $key;
    }
}

?>
