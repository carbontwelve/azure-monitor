<?php namespace Carbontwelve\AzureMonitor\Tests;

class InitCommandTest extends CommandTestBase
{
    /**
     * Test that we are within the right path for Jigsaw to be tested
     */
    public function testCurrentWorkingDirectoryIsTestTemp()
    {
        $this->assertEquals(self::$tmpPath, getcwd());
    }

    public function testDefaultInit()
    {
        $output = $this->runCommand('init');
        $this->assertEquals(0, $output->getStatusCode());
    }
}
