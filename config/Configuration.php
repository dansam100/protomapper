<?php
namespace ProtoMapper\Config;
/**
* Exception thrown when loading invalid configuration files
*/
class ConfigurationLoaderException extends \Exception{}

/**
    * Loader for web.config configuration parameters
    */
class Configuration extends ConfigLoader
{    
    const WEB_CONFIG = 'protomapper\\tests\\examples\\protocol.config.xml';
    
    private static $appConfig;
    private $protocols;

    public function __construct()
    {
        $this->site_map = array();
        $this->config_location = BASE_DIR . DS . self::WEB_CONFIG;
        $this->loadConfig($this->config_location);
    }

    /**
     * Returns an instance that contains web.config configuration parameters
     * @return \Rexume\Config\Configuration
     */
    public static function getInstance()
    {
        if(isset(self::$appConfig)){
            return self::$appConfig;
        }
        else{
            return self::$appConfig = new Configuration();
        }
    }

    function loadConfig($protocolsConfig)
    {
        if(!file_exists($protocolsConfig))
        {
            throw new ConfigurationLoaderException("Web configuration file: '" . $protocolsConfig . " could not be found!");
        }        
        //LOAD: Load protocols for parsing data
        $protocol_xml = simplexml_load_file($protocolsConfig);
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
}