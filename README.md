
#protomapper#

The purpose of this project is two-fold.<br/>
1.	To be a nexus between various types of "data providing protocols" and your project. PHP needs a standard way of interfacing and reading data from protocols like oAuth.  
2.	An internal mapper for establishing various kinds of relationships between objects and other objects, xml documents and objects, etc.

##synopsis##
1.	See <http://www.github.com/dansam100/protomapper/tree/master/tests/config/ConfigLoaderTest.php> for a sample of how to initialize protomapper from configuration.
2.	See <http://www.github.com/dansam100/protomapper/tree/master/tests/config/ProtocolDefinitionTest.php> to see how parsing works.

For a more in-depth view, take a look at the parsers which do the real work under: http://www.github.com/dansam100/protomapper/tree/master/parsers/

I will publish a proper help page if there is some demand for it.

##description##
protomapper attempts to map data from any provider to your model using a simple xml configuration file. It attempts to use the notion of model-driven engineering to establish mappings
between the data being mapped and your model (which needs to be mapped to) using various transformations and type conversions. The transformations are done with the help of a library of parsers which come with the solution.

###reading data###
One of the aims of this library is to standardize reading from multiple data sources. Thus, the library will establish a number of 'readers' which will implement a number of interfaces that can be used to access data from
any form of external provider (eg: json provider, oAuth provider, CSV documents, etc). The reader will have to be specified in the protomapper xml configuration and the rest of the work will be taken care of by the 'Mapping'
engine of protomapper. 

###mapping data###
This library leverages the use of multiple callbacks (provided by the caller) to determine the meaning of retrieved values from the 'source' or provider. Therefore, the only things required to ever parse and map data from an external module to
your model will be to write a parser that can read the data from the provider in a set of predefined ways. The implementation detail of this parser is up to your own discretion. The parser must, however, implement a given
set of interfaces to ensure it is usable by the protomapper core. That is it!

With this in mind, we can write a number of parsers for multiple document types, protocol response types (eg: json, xml, etc) using the interface rules and be able to map any form of data to our models by just defining the
protomapper xml configuration file and referencing the right parsers.

###current state###
Currently, protomapper only works for parsing xml documents since I have only implemented a simple xml parser (<http://www.github.com/dansam100/protomapper/tree/master/parsers/XMLSimpleParser.php>).
This works very well up to the inclusion of many mini sub parsers to help with the data mapping.

I have code in there to do recursive reads and parsing for nested objects but that does not work in the current checkin. I will update that in the future.
