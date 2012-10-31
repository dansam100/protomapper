<?php
namespace ProtoMapper\Parsers;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author sam.jr
 */
interface IParser extends IValueParser{
    function getValue($content, $callback);
    function getValues($content, $key);
    function parse($content, $callback);
}
