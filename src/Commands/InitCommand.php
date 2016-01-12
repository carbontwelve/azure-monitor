<?php namespace Carbontwelve\AzureMonitor\Commands;

class InitCommand extends Command
{
    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Init Wizard');
    }

    /**
     * @return int
     */
    protected function fire()
    {
        $this->info('Hello world!');
    }
}
