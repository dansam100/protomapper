<?php

namespace ProtoMapper\Parsers;
use ProtoMapper\Binds\ProtocolBind as ProtocolBind;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-18 at 02:24:52.
 */
class LinkedInAddressParserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var LinkedInAddressParser
     */
    protected $object;
    
    private $simpleAddress1 = <<<EOL
    11 Street Name
    PO Box 11
    City Province P0S7A1
    Country
EOL;
    private $simpleAddressWithName1 = <<<EOL
    Name
    11 Street Name
    PO Box 11
    City Province P0S7A1
    Country
EOL;
    private $simpleAddressOneLine1 = '11 Street Name, PO Box 11, City, Province P0S7A1, Country';

    private $simpleAddress2 = <<<EOL
    11 Street Name,
    P.O. Box 11,
    City Province P0S7A1,
    Country
EOL;
    
    private $simpleAddressWithName2 = <<<EOL
    MARY ROE
    11 Street Name,
    P.O. Box 11,
    City Province P0S7A1,
    Country
EOL;
    private $simpleAddressWithCompanyAndName2 = <<<EOL
    MARY ROE
    MEGASYSTEMS INC
    421 E DRACHMAN ST SUITE 5A
    TUCSON AZ 85705-7598
    USA
EOL;
    private $simpleAddressWithName3 = <<<EOL
    JANE ROE
    421 E DRACHMAN ST
    TUCSON AZ 85705-7598
    USA
EOL;
    private $simpleAddressWithComplexPostalCode3 = <<<EOL
    JOHN "GULLIBLE" DOE
    CENTER FOR FINANCIAL ASSISTANCE TO DEPOSED NIGERIAN ROYALTY
    421 E DRACHMAN ST
    TUCSON AZ 85705-7598
    USA
EOL;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->type = 'Address';
        $this->mappings = array(
            new ProtocolBind(".", "street1"),
            new ProtocolBind(".", "street2"),
            new ProtocolBind(".", "city"),
            new ProtocolBind(".", "province"),
            new ProtocolBind(".", "postalCode"),
            new ProtocolBind(".", "country")
        );
        $createTag = function($content){ return '<value>' . $content . '</value>'; };
        $this->testAddresses1 = array(
            new \SimpleXMLElement($createTag($this->simpleAddress1)),
            new \SimpleXMLElement($createTag($this->simpleAddressWithName1)),
            new \SimpleXMLElement($createTag($this->simpleAddressOneLine1))
        );
        $this->testAddresses2 = array(
            new \SimpleXMLElement($createTag($this->simpleAddress2)),
            new \SimpleXMLElement($createTag($this->simpleAddressWithName2))
        );
        $this->testAddresses3 = array(
            new \SimpleXMLElement($createTag($this->simpleAddressWithCompanyAndName2)),
            new \SimpleXMLElement($createTag($this->simpleAddressWithName3)),
            new \SimpleXMLElement($createTag($this->simpleAddressWithComplexPostalCode3))
        );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Rexume\Lib\Parsers\LinkedInAddressParser::parse
     */
    public function testParse() {
        $this->object = new SimpleAddressParser($this->mappings, $this->type);
        foreach($this->testAddresses1 as $testAddress){
            $result = $this->object->parse($testAddress);
            //check stuff
            $this->assertInstanceOf($this->type, $result);
            $this->assertEquals("11 Street Name", $result->street1());
            $this->assertEquals("PO Box 11", $result->street2());
            $this->assertEquals("City", $result->city());
            $this->assertEquals("P0S7A1", $result->postalCode());
            $this->assertEquals("Country", $result->country());
            $this->assertEquals("Province", $result->province());
        }
        $this->object = new SimpleAddressParser($this->mappings, $this->type);
        foreach($this->testAddresses2 as $testAddress){
            $result = $this->object->parse($testAddress);
            //check stuff
            $this->assertInstanceOf($this->type, $result);
            $this->assertEquals("11 Street Name", $result->street1());
            $this->assertEquals("P.O. Box 11", $result->street2());
            $this->assertEquals("City", $result->city());
            $this->assertEquals("P0S7A1", $result->postalCode());
            $this->assertEquals("Country", $result->country());
            $this->assertEquals("Province", $result->province());
        }
        $this->object = new SimpleAddressParser($this->mappings, $this->type);
        foreach($this->testAddresses3 as $testAddress){
            $result = $this->object->parse($testAddress);
            //check stuff
            $this->assertInstanceOf($this->type, $result);
            $this->assertEquals("421 E DRACHMAN ST", $result->street1());
            $this->assertEmpty($result->street2());
            $this->assertEquals("TUCSON", $result->city());
            $this->assertEquals("85705-7598", $result->postalCode());
            $this->assertEquals("USA", $result->country());
            $this->assertEquals("AZ", $result->province());
        }
    }

}
