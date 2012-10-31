<?php
namespace ProtoMapper\Parsers;;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CompoundDelimitedParser
 *
 * @author sam.jr
 */
class CompoundDelimitedParser extends DelimitedParser
{
    public function __construct($mappings, $type, $delimiters = array('\n', ',')){
        parent::__construct($mappings, $type, implode("", $delimiters));
    }
}