<?php
namespace ProtoMapper\Parsers;
/**
 * Date parser for xml structure.
 * 
 * <date>
 *  <month></month>
 *  <year></year>
 *  <day></day>
 * </date>
 *
 * @author sam.jr
 */
class XMLDateParser
{
    /**
     *
     * @var ProtocolBind[]
     */
    private $mappings;
    private $year;
    private $month;
    private $day;
    
    /**
     * Ctor
     * @param ProtocolBind[] $mappings
     */
    public function __construct($mappings, $type = null) {
        $this->year = 1970;
        $this->month = 1;
        $this->day = 1;
        $this->mappings = $mappings;
    }
    
    /**
     * 
     * @param \SimpleXMLElement[] $content
     * @param IParser $callback
     * @return date
     */
    public function parse($content, $callback)
    {
        foreach($this->mappings as $mapping)
        {
            $result = $callback->getValue($content, $mapping->source());
            if(isset($result)){
                $target = $mapping->target();
                $this->$target = $mapping->parse($result, $callback);
            }
        }
        return new \DateTime(date(DATE_ATOM, mktime(0,0,0,$this->month, $this->day, $this->year)));
    }
}
