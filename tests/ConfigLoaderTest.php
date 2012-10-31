<?php
namespace ProtoMapper\Test;
require_once("../config/AutoLoad.php");
require_once("examples/types.php");
use ProtoMapper\Config\Configuration as Configuration;

//BEGIN: test loading using the configuration file in "/examples/protocol.config.xml"
$loader = Configuration::getInstance();
$protoDef = $loader->getProtocolDefinition("LinkedIn", "Data");
assert(!empty($protoDef));
$sampleFile = \getWebContent('examples//sample.xml');
$parsedContent = $protoDef->parse($sampleFile);
//parsing should work
var_dump($parsedContent);
	