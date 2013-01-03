<?php
namespace ProtoMapper\Binds;

/**
 * ProtocolMapping.php
 * ProtocolMapping class for each binding
 * @author sam.jr 
 */
class ProtocolObject extends ProtocolMapping
{
    
    public function initialize(){
        //@var Entity resulting entity to return
        $result = $this->defaultValue();
        if(!isset($result)){
            $result = parent::initialize();
        }
        return $result;
    }
    
    public function defaultValue(){
        return \ProtoMapper\Config\ConfigLoader::checkEvaluatable($this->default, $this->type);
    }
}