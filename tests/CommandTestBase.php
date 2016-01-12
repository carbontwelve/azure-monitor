<?php namespace Carbontwelve\AzureMonitor\Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

abstract class CommandTestBase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|\Symfony\Component\Console\Application
     */
    protected $cli;

    /**
     * @var string
     */
    protected static $tmpPath;

    /**
     * Before the test cases are run, change directory to the tests directory and set the _tmp path
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$tmpPath = __DIR__ . DIRECTORY_SEPARATOR . '_tmp';
        if (!file_exists(self::$tmpPath)) {
            mkdir(self::$tmpPath);
        }
        chdir(self::$tmpPath);
    }

    /**
     * @return Application
     */
    private function createCliApplication()
    {
        /** @var Application $jigsaw */
        $app = require __DIR__ . '/../src/bootstrap.php';

        /** @var \Symfony\Component\Console\Application $cli */
        $cli = $app[Application::class];
        $cli->setAutoExit(false);
        return $cli;
    }

    /**
     * Using the cli application itself, execute a command that already exists
     *
     * @param string $command
     * @param array $arguments
     * @return ApplicationTester
     */
    protected function runCommand($command, array $arguments = [])
    {
        $applicationTester = new ApplicationTester($this->getCli());
        $arguments = array_merge(['command' => $command], $arguments);
        $applicationTester->run($arguments);
        return $applicationTester;
    }

    /**
     * Obtain the cli application for testing
     * @return Application
     */
    protected function getCli()
    {
        if (is_null($this->cli)) {
            $this->cli = $this->createCliApplication();
        }

        return $this->cli;
    }
}
