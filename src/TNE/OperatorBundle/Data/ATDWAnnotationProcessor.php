<?php

/**
 *  @todo Pull config out of code
 *  @todo Externalise atdw WSDL service
 */

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
    
    private $driver;

    private $distributorKey = '201212040953';
    
    private $atdwCategoryList;
    
    private $atdwResults;
    
    private $productsXml;
    
    private $productXml = array();


    public function __construct(MetadataFactoryInterface $metadataFactory, $driver)
    {
        $this->metadataFactory = $metadataFactory;
        $this->driver = $driver;
    }
    
    public function bootstrap($className)
    {
        $entityAnnotation = $this->driver->getReader()->getClassAnnotation($className, 'TNE\\OperatorBundle\\Annotation\\ATDW\\Entity');
        
        $this->loadXml(array("category"=>$entityAnnotation->getType()));
    }

    public function loadXml($options)
    {
        $strWSDL = 'http://national.atdw.com.au/soap/AustralianTourismWebService.asmx?WSDL';
        $client = new \SoapClient($strWSDL);
        $strCommandName = "QueryProducts";
        $strCommandParameter = "<parameters>";
        $strCommandParameter .= "<row><param>PRODUCT_CATEGORY_LIST</param><value>$options[category]</value></row>";
        $strCommandParameter .= "<row><param>RESULTS_PER_PAGE</param><value>1000</value></row>";
        $strCommandParameter .= "<row><param>DOMESTIC</param><value>Victoria's High Country</value></row>";
        $strCommandParameter .= "<row><param>ADDRESS_RETURN</param><value>YES</value></row>";        
        $strCommandParameter .= "</parameters>";
        
        
        $param = array('DistributorKey'=> $this->distributorKey, 'CommandName'=>$strCommandName, 'CommandParameters'=>$strCommandParameter);
        $result = $client->CommandHandler($param);

        $xmlUf8 = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $result->CommandHandlerResult);
        
        echo $xmlUf8;
        
        $this->atdwResults = simplexml_load_string($xmlUf8);        
    }

    public function populate($object, $index=1, $em=null)
    {         
        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($object));
        echo "\n----------------------------------------------------------------------------------------------------\n Importing: " . $object->getName() . " \n\n";
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (isset($propertyMetadata->xpathString)) {
                if(($propertyMetadata->commandType == 'set1'))
                {
                    $atdwValue = $this->getAtdwResults()->xpath(str_replace('$index', $index, $propertyMetadata->xpathString));    
                    if(isset($atdwValue[0]))
                    {
                        // Handle date type
                        $propertyMetadata->setValue($object, (string) $atdwValue[0]);
                    }
                }
                else
                {                    
                    $atdwValue = $this->getProductXml($object->getAtdwId())->xpath($propertyMetadata->xpathString);
                    
                    if(property_exists($propertyMetadata, 'addMethod'))
                    {
                        $addMethod = $propertyMetadata->addMethod;
                        foreach($atdwValue as $row)
                        {
                            $objectClassName = $propertyMetadata->objectClassName;
                            $subObject = $this->fillObject(new $objectClassName(), $row);
                            //\Doctrine\Common\Util\Debug::dump($subObject);
                            $object->$addMethod($subObject);
                        }
                    }
                    else if(property_exists($propertyMetadata, 'setManyToManyMethod'))
                    {
                        $setManyToManyMethod = $propertyMetadata->setManyToManyMethod;
                        foreach($atdwValue as $row)
                        {
                            /**
                             * @fixme Special Case - assuming structure about the property, convert to generic use!
                             */

                            echo "\n\n -- Loading classifications -- \n\n";

                            var_dump($row);

                            $repoName = $propertyMetadata->repoName;
                            $r = $em->createQuery(
                                'SELECT r FROM ' . $repoName . ' r' . ' WHERE r.keyStr = :atdwKey'
                            )
                            ->setParameter('atdwKey', $row->product_type_id)
                            ->getSingleResult();

                            $r->$setManyToManyMethod($object);
                            \Doctrine\Common\Util\Debug::dump($r);
                        }
                    }
                    else
                    {
                        if(isset($atdwValue[0])) $propertyMetadata->setValue($object, (string) $atdwValue[0]);
                    }
                }
            }
        }
 
        return $object;
    }
    
    public function fillObject($object, $xpathNode)
    {
        foreach ($xpathNode as $key => $value)
        {
            $object->setField($key, (string) $value);
        }
        return $object;
    }


    public function getAtdwResults()
    {
        return $this->atdwResults;
    }
    
    public function getProductCount()
    {
        return count($this->getAtdwResults()->xpath('/atdw_data_results/product_distribution'));
    }    


    public function getProductXml($productId)
    {
        $productKey = (string) $productId;
        if(!array_key_exists($productKey, $this->productXml))
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
//            echo $xmlUf8;
//            echo "\n\n\n\n\_______________________________________________________________________________________________________\n\n\n\n\n";

            $this->productXml[$productKey] = simplexml_load_string($xmlUf8);        
        }
        return $this->productXml[$productKey];
    }
    
}
