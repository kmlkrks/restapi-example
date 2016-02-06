<?php

namespace ApiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateConsumerKeyCommand
 * @package ApiBundle
 * @author Kemal KARAKAŞ <kmlkarakas@gmail.com>
 */
class GenerateConsumerKeyCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('generate:consumer-key')
            ->setDescription('Generate new consumer key');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $consumerKey = md5(time());
        $output->writeln(sprintf('<info>%s</info>', $consumerKey));
    }
} 