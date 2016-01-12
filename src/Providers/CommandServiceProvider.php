<?php namespace Carbontwelve\AzureMonitor\Providers;

use Carbontwelve\AzureMonitor\Monitor;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\Console\Application;

class CommandServiceProvider extends AbstractServiceProvider
{

    /**
     * @var array
     */
    protected $provides = [
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
        $this->getContainer()->add(Application::class, function(){
            return new Application('AzureMonitor', Monitor::VERSION);
        });
    }
}
