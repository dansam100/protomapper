<?php
require_once("Util.php");
$PROTO_BASE_DIR = dirname(dirname(__FILE__));
$loaders = array(
	new ClassLoader("ProtoMapper\Binds", $PROTO_BASE_DIR),
	new ClassLoader("ProtoMapper\Config", $PROTO_BASE_DIR),
	new ClassLoader("ProtoMapper\Definition", $PROTO_BASE_DIR),
	new ClassLoader("ProtoMapper\Parsers", $PROTO_BASE_DIR)
);
foreach ($loaders as $loader) {
	$loader->register();
}
class ClassLoader{
	private $namespace;
	private $rootPath;
	private $namespaceSeparator = '\\';
	public function __construct($namespace, $rootPath){
		$this->namespace = $namespace;
		$this->rootPath = $rootPath;
	}
	
	public function register()
	{
		spl_autoload_register(array($this, "autoload"));
	}
	
	public function unregister()
	{
		spl_autoload_unregister(array($this, "autoload"));
	}
	
	public function contains($className){
		$split = explode($this->namespaceSeparator, $className);
		$class = array_pop($split);
		$nsOnly = implode($this->namespaceSeparator, $split);
		return (strcmp($this->namespace, $nsOnly) == 0);
		
	}
	
	public function autoload($className)
	{
		if($this->contains($className)){
			$path = $this->rootPath . DIRECTORY_SEPARATOR . trim($className, '\\') . ".php";
			if(is_readable($path)){
				require_once($path);
				return true;
			}
		}
		return false;
	}
}
