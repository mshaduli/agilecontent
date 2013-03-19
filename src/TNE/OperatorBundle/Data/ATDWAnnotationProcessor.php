<?php

namespace TNE\OperatorBundle\Data;

use Metadata\MetadataFactoryInterface;

/**
 * Description of ATDWAnnotationProcessor
 *
 * @author zuhairnaqvi
 */
class ATDWAnnotationProcessor {
    private $metadataFactory;
    
    private $distributorKey = '201212040953';


    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }
 
    public function populate($object)
    {
        if (!method_exists($object, 'getAtdwId')) {
            throw new \InvalidArgumentException('Not an ATDW compatible object');
        }
        
        
        $strWSDL = 'http://national.atdw.com.au/soap/AustralianTourismWebService.asmx?WSDL';
        $client = new \SoapClient($strWSDL);        
        
        $strCommandName = "GetProduct";
        $strCommandParameter = "<parameters>";
        $strCommandParameter .= "<row><param>PRODUCT_ID</param><value>".$object->getAtdwId()."</value></row>";
        $strCommandParameter .= "</parameters>";
        
        $param = array('DistributorKey'=> $this->distributorKey, 'CommandName'=>$strCommandName, 'CommandParameters'=>$strCommandParameter);
        $result = $client->CommandHandler($param);

        $xmlUf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $result->CommandHandlerResult);
        $xmlObj = simplexml_load_string($xmlUf8);
        $productRecord = $xmlObj->product_distribution->product_record;
        
 
        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($object));
        
 
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (isset($propertyMetadata->atdwKey)) {
                $atdwKey = $propertyMetadata->atdwKey;
                $propertyMetadata->setValue($object, (string) $productRecord->$atdwKey);
            }
        }
 
        return $object;
    }
    
}
