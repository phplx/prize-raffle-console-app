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

use Phplx\Application;
use Phplx\Command\FetchAttendeesCommand;
use Phplx\Command\ListPrizesCommand;
use Phplx\Command\ListWinnersCommand;
use Phplx\Command\LoadPrizesCommand;
use Phplx\Command\RaffleCommand;
use Phplx\Command\TweetCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$configDirectories = array(
    __DIR__ . '/../config'
);

$container = include __DIR__ . '/../src/container.php';

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