<?php

namespace TNE\OperatorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TNE\OperatorBundle\Entity\Accommodation;

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
            ->addOption('all', null, InputOption::VALUE_NONE, 'If set, will batch import products')                
            ->setDescription('Load operators from ATDW')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $atdwProcessor = $this->getContainer()->get('tne_annotation.atdw_processor');
        
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        
        if($input->getOption('all'))
        {
            $atdwProcessor->import($em);
        }
            
        else 
        {             
            $accommodation = $em->find('TNE\OperatorBundle\Entity\Accommodation', $input->getArgument('id'));
            $atdwProcessor->populate($accommodation);
            //\Doctrine\Common\Util\Debug::dump($accommodation);
            $em->persist($accommodation);
            $em->flush();
       }
        
        
        $output->writeln("processed");
    }
}

