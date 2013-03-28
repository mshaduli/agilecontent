<?php

namespace TNE\OperatorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Description of GeoExtensionCompilerPass
 *
 * @author zuhairnaqvi
 */

class GeoExtensionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('vich_geographical.twig.extension')) {
            return;
        }
        
        var_dump('service found');

        $definition = new Definition('TNE\OperatorBundle\Twig\Extension\GeographicalExtension', array($container->getDefinition('vich_geographical.templating.helper.map_helper')));
        
        $container->setDefinition('vich_geographical.twig.extension', $definition);

    }
}