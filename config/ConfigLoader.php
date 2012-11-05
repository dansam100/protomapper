<?php
namespace ProtoMapper\Config;
use ProtoMapper\Definition\ProtocolDefinition as ProtocolDefinition;
use ProtoMapper\Binds\ProtocolObject as ProtocolObject;
use ProtoMapper\Binds\ProtocolBind as ProtocolBind;

/**
* Exception thrown when loading invalid configuration files
*/
class ConfigurationLoaderException extends \Exception{}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProtocolParser
 *
 * @author sam.jr
 */
/**
 * Trait for parsing protocol definitions
 */
class ConfigLoader
{
    private $protocols;
    
    public function load($protocolConfigLocation)
    {
        if(!file_exists($protocolConfigLocation))
        {
            throw new ConfigurationLoaderException("Web configuration file: '" . $protocolConfigLocation . " could not be found!");
        }        
        //LOAD: Load protocols for parsing data
        $protocol_xml = simplexml_load_file($protocolConfigLocation);
        $this->protocols = $this->parseProtocols($protocol_xml);
    }
    
    /**
     * Gets a protocol definition configured for use when requesting information regarding user details
     * @param string $name The name of the protocol to fetch
     * @return ProtocolDefinition the protocol definition matching the data type
     */
    public function getProtocolDefinition($name, $type)
    {
        return $this->protocols[$name][$type];
    }
    
    protected function createBinding(\SimpleXmlElement $bind)
    {
        return new ProtocolBind
        (
            (string)$bind['source'], 
            (string)$bind['target'], 
            (string)$bind['type'],
            (string)$bind['name'],
            (string)$bind['default'],
            (string)$bind['parser'],
            array_map(array($this, 'createBinding'), $bind->xpath('data/bind'))
        );
    }
    
    
    /**
     * Parses a mapping xml definition for a given protocol
     * @param \SimpleXmlElement $mapping the list of mappings to parse
     * @return \Rexume\Config\ProtocolMapping The created protocol mapping
     */
    protected function createMapping(\SimpleXmlElement $mapping)
    {
        $protocol = null;
        $bindings = array_map(array($this, 'createBinding'), $mapping->xpath('bind'));
        if($mapping->read){
            $protocol = $this->parseProtocol
            (
                (string)$mapping->read['name'], 
                (string)$mapping->read['type'], 
                $mapping->read->definition,
                (string)$mapping->read['parser']
            );
        }
        return new ProtocolObject
        (
            (string)$mapping['name'],
            (string)$mapping['type'],
            null,
            $protocol,
            $bindings,
            (string)$mapping['parser']
        );
    }
    
    /**
     * Parses a protocol xml file into respective protocol
     * @param \SimpleXMLElement $protocol_xml the xml defintion for a protocol
     * @return ProtocolDefintion[] a collection of parsed protocols
     */
    public function parseProtocols($protocol_xml)
    {
        $protocolDefs = $protocol_xml->definition;
        $result = array();
        //parse xml and create protocol and protocol mapping definitions
        foreach ($protocolDefs as $protocolDef) {
            foreach($protocolDefs->read as $readDef){
                $protocol = $this->parseProtocol
                    (
                        (string)$protocolDef['name'],
                        (string)$protocolDef['type'], 
                        $readDef,
                        (string)$protocolDef['parser']
                    );
                if(!(array_key_exists($protocol->name(), $result))){
                    $result[$protocol->name()] = array();
                }
                
                $result[$protocol->name()][$protocol->contentType()] = $protocol;
            }
        }
        return $result;
    }
    
    /**
     * Parses the xml configuration file to create protocol definitions used to read and parse data
     * @param string $name the name of the authentication scheme (eg: "LinkedIn", "Twitter", etc)
     * @param string $type the type represents the protocol type (eg: "REST", "FILE", etc)
     * @param \SimpleXMLElement $readDef the definition xml
     * @param string $parser the name of the parser class to use
     * @return \Rexume\Config\ProtocolDefinition the created protocol defintion
     */
    protected function parseProtocol($name, $type, $readDef, $parser)
    {
        $objects = array_map(array($this, 'createMapping'), $readDef->xpath('object'));
        $mappings = array_map(array($this, 'createMapping'), $readDef->xpath('mappings/mapping'));        
        return new ProtocolDefinition
        (
            $name, 
            $type,
            (string)$readDef['contenttype'],
            (string)$readDef['scope'],
            (string)$readDef->query,
            $objects,
            $mappings,
            $parser
        );
    }
}
