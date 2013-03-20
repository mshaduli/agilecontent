<?php

namespace TNE\OperatorBundle\Data;

use Metadata\MetadataFactoryInterface;
use TNE\OperatorBundle\Entity\Accommodation;

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
        
        

        $productRecord = $this->getProduct($object->getAtdwId())->product_record;
         
        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($object));
        
 
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (isset($propertyMetadata->atdwKey)) {
                $atdwKey = $propertyMetadata->atdwKey;
                $propertyMetadata->setValue($object, (string) $productRecord->$atdwKey);
            }
        }
 
        return $object;
    }
    
    public function import($em)
    {
        $strWSDL = 'http://national.atdw.com.au/soap/AustralianTourismWebService.asmx?WSDL';
        $client = new \SoapClient($strWSDL);
        $strCommandName = "QueryProducts";
        $strCommandParameter = "<parameters>";
        $strCommandParameter .= "<row><param>PRODUCT_CATEGORY_LIST</param><value>ACCOMM</value></row>";
        $strCommandParameter .= "<row><param>RESULTS_PER_PAGE</param><value>1000</value></row>";
        $strCommandParameter .= "<row><param>DOMESTIC</param><value>Victoria's High Country</value></row>";
        $strCommandParameter .= "<row><param>ADDRESS_RETURN</param><value>YES</value></row>";        
        $strCommandParameter .= "</parameters>";
        
        
        $param = array('DistributorKey'=> $this->distributorKey, 'CommandName'=>$strCommandName, 'CommandParameters'=>$strCommandParameter);
        $result = $client->CommandHandler($param);

        $xmlUf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $result->CommandHandlerResult);
        $xmlObj = simplexml_load_string($xmlUf8);

        foreach ($xmlObj as $result)
        {
            echo $result->product_record->product_id . " : ". $result->product_record->product_name . " : ". $result->product_address->row->address_line_1 . " : ". $result->product_address->row->city_name;
            $accommodation = new Accommodation();
            $accommodation->setAtdwId($result->product_record->product_id);
            $accommodation->setName($result->product_record->product_name);
            $accommodation->setDescription($result->product_record->product_description);
            $accommodation->setAddress($result->product_address->row->address_line_1 . ", ". $result->product_address->row->city_name . ", " . $result->product_address->row->state_name . ", ". $result->product_address->row->address_postal_code);
            $accommodation->setDestination($result->product_address->row->city_name);
            $em->persist($accommodation);
            $em->flush();
            
//            var_dump($this->getProduct($result->product_record->product_id)->product_attribute);
            echo "\n\n\n\n\n-------------------------------------------------------------------------------------\n\n\n\n\n";
        }
        
    }
    
    public function getProduct($productId)
    {
        $strWSDL = 'http://national.atdw.com.au/soap/AustralianTourismWebService.asmx?WSDL';
        $client = new \SoapClient($strWSDL);        
        
        $strCommandName = "GetProduct";
        $strCommandParameter = "<parameters>";
        $strCommandParameter .= "<row><param>PRODUCT_ID</param><value>".$productId."</value></row>";
        $strCommandParameter .= "</parameters>";
        
        $param = array('DistributorKey'=> $this->distributorKey, 'CommandName'=>$strCommandName, 'CommandParameters'=>$strCommandParameter);
        $result = $client->CommandHandler($param);

        $xmlUf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $result->CommandHandlerResult);
        $xmlObj = simplexml_load_string($xmlUf8);
        return $xmlObj->product_distribution;
    }
    
}
