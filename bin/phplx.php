<?php
/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '/../vendor/autoload.php';

use Phplx\Raffle\Application;
use Phplx\Raffle\Command\FetchAttendeesCommand;
use Phplx\Raffle\Command\ListPrizesCommand;
use Phplx\Raffle\Command\ListWinnersCommand;
use Phplx\Raffle\Command\LoadPrizesCommand;
use Phplx\Raffle\Command\RaffleCommand;
use Phplx\Raffle\Command\TweetCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$configDirectories = array(
    __DIR__ . '/../config'
);

$container = include __DIR__ . '/../src/Phplx/Raffle/container.php';

$loader = new YamlFileLoader($container, new FileLocator($configDirectories));
$loader->load('parameters.yaml');

$fetchAttendeesCmd = new FetchAttendeesCommand();
$loadPrizesCmd = new LoadPrizesCommand();
$raffleCmd = new RaffleCommand();
$listPrizesCmd = new ListPrizesCommand();
$listWinnersCmd = new ListWinnersCommand();
$sendTweetCmd = new TweetCommand();

$app = new Application('phplx Prize Raffle Console Application', Application::VERSION);
$app->setContainer($container);

$app->addCommands(
    array(
         $fetchAttendeesCmd,
         $loadPrizesCmd,
         $listPrizesCmd,
         $listWinnersCmd,
         $raffleCmd,
         $sendTweetCmd
    )
);
$app->run();