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
    private $recurse;
    private $fileExtension = '.php';
	public function __construct($namespace, $rootPath, $recurse = false){
		$this->namespace = $namespace;
		$this->rootPath = $rootPath;
        $this->recurse = $recurse;
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
        if($this->namespace == "*"){
            return true;
        }
		$split = explode($this->namespaceSeparator, $className);
		$class = array_pop($split);
		$nsOnly = implode($this->namespaceSeparator, $split);
		return (strcmp($this->namespace, $nsOnly) == 0);
		
	}
	
	public function autoload($className)
	{
		if($this->contains($className)){
			$path = $this->rootPath . DIRECTORY_SEPARATOR . trim($className, '\\') . $this->fileExtension;
			if(is_readable($path)){
				require_once($path);
				return true;
			}
            elseif($this->recurse){
                $folder = (($this->rootPath !== null) ? $this->rootPath . DIRECTORY_SEPARATOR : '') . $this->namespace;
                $file = mb_substr(strrchr($className, '\\'), 1) . $this->fileExtension;
                $files = find($folder, $file);
                if(empty($files)) return false;
                foreach($files as $file){ require_once $file; }
                return true;
            }
		}
		return false;
	}
}
