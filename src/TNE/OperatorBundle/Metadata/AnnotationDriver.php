<?php

namespace TNE\OperatorBundle\Metadata;
 
use Metadata\Driver\DriverInterface;
use Metadata\MergeableClassMetadata;
use Doctrine\Common\Annotations\Reader;
use TNE\OperatorBundle\Metadata\PropertyMetadata;
 
class AnnotationDriver implements DriverInterface
{
    private $reader;
 
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
 
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new MergeableClassMetadata($class->getName());
 
        foreach ($class->getProperties() as $reflectionProperty) {
           $propertyMetadata = new PropertyMetadata($class->getName(), $reflectionProperty->getName());
            
            foreach ($this->reader->getPropertyAnnotations($reflectionProperty) as $annotation) {
                
                
                if(method_exists($annotation, 'getAtdwKey'))               {
                    $propertyMetadata->atdwKey = $annotation::getAtdwKey();
                    $classMetadata->addPropertyMetadata($propertyMetadata);
                }
            }           
        }
 
        return $classMetadata;
    }
}