<?php
namespace ProtoMapper\Config;
use ProtoMapper\Definition\ProtocolDefinition as ProtocolDefinition;
use ProtoMapper\Binds\ProtocolObject as ProtocolObject;
use ProtoMapper\Binds\ProtocolBind as ProtocolBind;
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
    function createBinding(\SimpleXmlElement $bind)
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
    function createMapping(\SimpleXmlElement $mapping)
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
        //var_dump($result['LinkedIn']['Data']->targets()[0]->bindings());
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
    public function parseProtocol($name, $type, $readDef, $parser)
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
