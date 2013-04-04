<?php

namespace TNE\OperatorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TNE\OperatorBundle\Entity\Accommodation;
use TNE\OperatorBundle\Entity\Event;

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
            ->setDescription('Load operators from ATDW')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $atdwProcessor = $this->getContainer()->get('tne_annotation.atdw_processor');        
        
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        
        if($input->getOption('type')=='accomm')
        {
            $atdwProcessor->bootstrap(new \ReflectionClass('\TNE\OperatorBundle\Entity\Accommodation'));
            for($i=1;$i<=$atdwProcessor->getProductCount();$i++)
            {
                $newAccommodation = new Accommodation();
                $atdwProcessor->populate($newAccommodation, $i);
                $em->persist($newAccommodation);
            }
            $em->flush();
        }
            
        else if ($input->getOption('type')=='event')
        {   
            
            $atdwProcessor->bootstrap(new \ReflectionClass('\TNE\OperatorBundle\Entity\Event'));
            for($i=1;$i<=$atdwProcessor->getProductCount();$i++)
            {
                $newEvent = new Event();
                $atdwProcessor->populate($newEvent, $i);
                $em->persist($newEvent);
            }
            $em->flush();

       }
        
        
        $output->writeln("processed");
    }
}

