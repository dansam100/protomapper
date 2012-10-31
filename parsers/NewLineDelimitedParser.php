<?php
namespace ProtoMapper\Parsers;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewLineDelimitedParser
 *
 * @author sam.jr
 */
class NewlineDelimitedParser extends DelimitedParser{
    public function __construct($mappings, $type, $delimiter = "\n") {
        parent::__construct($mappings, $type, $delimiter);
    }
}