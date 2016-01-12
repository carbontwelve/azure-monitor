<?php namespace Carbontwelve\AzureMonitor;

use League\Container\Container;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerInterface;
use League\Container\ServiceProvider\ServiceProviderInterface;

class Monitor implements ContainerAwareInterface, \ArrayAccess
{
    /**
     * @var \League\Container\ContainerInterface|\League\Container\Container
     */
    protected $container;

    /**
     * Monitor Version Number
     * @var string
     */
    const VERSION = '0.1.0';

    /**
     * Register a service provider
     *
     * @param  string|ServiceProviderInterface $serviceProvider
     * @return void
     */
    public function register($serviceProvider)
    {
        $this->getContainer()->addServiceProvider($serviceProvider);
    }

    /**
     * Set a container.
     *
     * @param \League\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        $this->container->add('app', $this);
    }

    /**
     * Get the container.
     *
     * @return \League\Container\ContainerInterface
     */
    public function getContainer()
    {
        if (!isset($this->container)) {
            $this->setContainer(new Container);
        }
        return $this->container;
    }


    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->getContainer()->has($offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getContainer()->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->getContainer()->add($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        // ... container doesn't allow this.
    }
}
