<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Raffle;

use Phplx\Raffle\Command\FetchAttendeesCommand;
use Phplx\Raffle\Command\ListPrizesCommand;
use Phplx\Raffle\Command\ListWinnersCommand;
use Phplx\Raffle\Command\LoadPrizesCommand;
use Phplx\Raffle\Command\RaffleCommand;
use Phplx\Raffle\Command\TweetCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class Application extends BaseApplication implements ContainerAwareInterface
{
    const VERSION = '1.1.1';
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct()
    {

        parent::__construct('phplx Prize Raffle Console Application', Application::VERSION);

        $this->loadContainerDefinitions();

        $this->add(new FetchAttendeesCommand());
        $this->add(new LoadPrizesCommand());
        $this->add(new RaffleCommand());
        $this->add(new ListPrizesCommand());
        $this->add(new ListWinnersCommand());
        $this->add(new TweetCommand());
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Loads and configures the DI Container definitions
     */
    private function loadContainerDefinitions()
    {
        $configDirectories = array(
            __DIR__ . '/../../../config'
        );

        $container = include __DIR__ . '/container.php';

        $loader = new YamlFileLoader($container, new FileLocator($configDirectories));
        $loader->load('parameters.yaml');

        $this->setContainer($container);
    }
}
