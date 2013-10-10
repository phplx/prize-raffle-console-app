<?php

namespace Phplx\Tests\Command;

use Phplx\Application;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

abstract class BaseCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Phplx\Application
     */
    protected $application;
    protected $cacheDir;

    public function setUp()
    {
        $this->cacheDir = __DIR__ . '/../../../../cache';

        $this->application = new Application();


        $configDirectories = array(
            __DIR__ . '/../../../../config',
            $this->cacheDir
        );

        $container = include __DIR__ . '/../../../../src/container.php';

        $loader = new YamlFileLoader($container, new FileLocator($configDirectories));
        $loader->load('parameters.yaml');

        $this->application->setContainer($container);
    }
}
