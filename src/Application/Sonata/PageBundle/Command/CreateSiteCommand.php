<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

/**
 * Create a site
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class CreateSiteCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('tne:sonata:page:create-site');

        $this->addOption('no-confirmation', null, InputOption::VALUE_OPTIONAL, 'Ask confirmation before generating the site', false);
        
        $this->addOption('region', null, InputOption::VALUE_OPTIONAL, 'Site.region', null);
        
        $this->addOption('enabled', null, InputOption::VALUE_OPTIONAL, 'Site.enabled', false);
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Site.name', null);
        $this->addOption('relativePath', null, InputOption::VALUE_OPTIONAL, 'Site.relativePath', null);
        $this->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Site.host', null);
        $this->addOption('enabledFrom', null, InputOption::VALUE_OPTIONAL, 'Site.enabledFrom', null);
        $this->addOption('enabledTo', null, InputOption::VALUE_OPTIONAL, 'Site.enabledTo', null);
        $this->addOption('default', null, InputOption::VALUE_OPTIONAL, 'Site.default', null);
        $this->addOption('locale', null, InputOption::VALUE_OPTIONAL, 'Site.locale', null);

        $this->addOption('base-command', null, InputOption::VALUE_OPTIONAL, 'Site id', 'php app/console');

        $this->setDescription('Create a destination site');

        $this->setHelp(<<<EOT
The <info>tne:sonata:page:create-site</info> command create a new site entity.

EOT
);

    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        $values = array(
            'name'        => null,
            'host'        => null,
            'region'        => null,            
            'relativePath'=> null,
            'enabled'     => null,
            'enabledFrom' => null,
            'enabledTo'   => null,
            'default'     => null,
            'locale'      => null,
        );

        foreach ($values as $name => $value) {
            $values[$name] = $input->getOption($name);

            while ($values[$name] == null) {
                $values[$name] = $dialog->ask($output, sprintf("Please define a value for <info>Site.%s</info> : ", $name));
            }
        }

        // create the object
        $site = $this->getSiteManager()->create();

        $site->setName($values['name']);

        $site->setRelativePath($values['relativePath'] == '/' ? '' : $values['relativePath']);
        $site->setRegion($values['region']);
        $site->setHost($values['host']);
        $site->setEnabledFrom(new \DateTime($values['enabledFrom']));
        $site->setEnabledTo(new \DateTime($values['enabledTo']));
        $site->setIsDefault($values['default']);
        $site->setLocale($values['locale'] == '-' ? null : $values['locale']);
        $site->setEnabled(in_array($values['enabled'], array('true', 1, '1')));

        $output->writeln(<<<INFO

Creating website with the following information :
  <info>name</info> : {$site->getName()}
  <info>site</info> : http(s)://{$site->getHost()}{$site->getRelativePath()}
  <info>enabled</info> :  {$site->getEnabledFrom()->format('r')} => {$site->getEnabledto()->format('r')}

INFO
);

        if ($input->getOption('no-confirmation') || $dialog->askConfirmation($output, 'Confirm site creation ?', false)) {
            $this->getSiteManager()->save($site);

            $output->writeln(array(
                '',
                '<info>Destination Site created !</info>',
                '',
                'You can now create the related pages and snapshots by running the followings commands:',
                sprintf('  php app/console sonata:page:update-core-routes --site=%s', $site->getId()),
                sprintf('  php app/console sonata:page:create-snapshots --site=%s', $site->getId()),
            ));

        } else {
            $output->writeln('<error>Site creation cancelled !</error>');
        }
    }
}
