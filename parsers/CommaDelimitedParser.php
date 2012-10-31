<?php
namespace ProtoMapper\Parsers;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommaDelimitedParser
 *
 * @author sam.jr
 */
class CommaDelimitedParser extends DelimitedParser{
    public function __construct($mappings, $type, $delimiter = ',') {
        parent::__construct($mappings, $type, $delimiter);
    }
}
