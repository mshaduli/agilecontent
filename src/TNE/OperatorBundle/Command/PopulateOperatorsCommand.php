<?php

namespace TNE\OperatorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TNE\OperatorBundle\Entity\Accommodation;
use TNE\OperatorBundle\Entity\Event;
use TNE\OperatorBundle\Entity\Attraction;
use TNE\OperatorBundle\Entity\Tour;
use TNE\OperatorBundle\Entity\Hire;
use TNE\OperatorBundle\Entity\Restaurant;

/**
 * Description of PopulateOperatorsCommand
 *
 * @author zuhairnaqvi
 */
class PopulateOperatorsCommand extends ContainerAwareCommand {
    protected function configure()
    {
        $this
            ->setName('tne:operators:load')
            ->addArgument('id', InputArgument::OPTIONAL, 'Choose accommodation recored to repopulate')                
            ->addOption('type', null, InputOption::VALUE_NONE, 'If set, will batch import products')
            ->addOption('pid', null, InputOption::VALUE_NONE, 'test product id')
            ->setDescription('Load operators from ATDW')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $atdwProcessor = $this->getContainer()->get('tne_annotation.atdw_processor');        
        
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        
        switch ($input->getOption('type')) {
            case 'accomm':
                $this->populate('Accommodation', $atdwProcessor, $em);
                break;
            case 'event':
                $this->populate('Event', $atdwProcessor, $em);
                break;
            case 'attr':
                $this->populate('Attraction', $atdwProcessor, $em);
                break;
            case 'tour':
                $this->populate('Tour', $atdwProcessor, $em);
                break;            
            case 'hire':
                $this->populate('Hire', $atdwProcessor, $em);
                break;
            case 'restaurant':
                $this->populate('Restaurant', $atdwProcessor, $em);
                break;
            case 'test':
                $atdwProcessor->getProductXml($input->getOption('pid'));
        }        
        
        $output->writeln("processed");
    }
    
    public function populate($entityName, $atdwProcessor, $em)
    {
        $className = '\\TNE\\OperatorBundle\\Entity\\'.$entityName;
        $atdwProcessor->bootstrap(new \ReflectionClass($className));
        for($i=1;$i<=$atdwProcessor->getProductCount();$i++)
        {
            $newRecord = new $className();
            $atdwProcessor->populate($newRecord, $i, $em);
            $em->persist($newRecord);
        }
        $em->flush();        
    }
}

