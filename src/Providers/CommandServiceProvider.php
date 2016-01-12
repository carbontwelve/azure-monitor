<?php namespace Carbontwelve\AzureMonitor\Providers;

use Carbontwelve\AzureMonitor\Commands\DisplayCommand;
use Carbontwelve\AzureMonitor\Commands\InitCommand;
use Carbontwelve\AzureMonitor\Monitor;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\Console\Application;

class CommandServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        InitCommand::class,
        Application::class
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $this->getContainer()->add(InitCommand::class);
        $this->getContainer()->add(DisplayCommand::class);

        $this->getContainer()->add(Application::class, function(){
            $cli = new Application('AzureMonitor', Monitor::VERSION);
            $cli->add($this->getContainer()->get(InitCommand::class));
            $cli->add($this->getContainer()->get(DisplayCommand::class));
            return $cli;
        });
    }
}
