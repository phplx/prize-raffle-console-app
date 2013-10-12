<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Raffle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class ListWinnersCommand extends Command
{
    /**
     * @See Command
     */
    protected function configure()
    {
        $this
            ->setName('meetup:prizes:winners')
            ->setDescription('Lists the winners of the prizes of an Event.')
            ->addArgument('event_id', InputArgument::REQUIRED, 'The event ID')
            ->setHelp(
                'The <info>meetup:prizes:winners</info> command will list the winners of the prizes of an event.'
            );
    }

    /**
     * @See Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataHandler = $this->getApplication()->getContainer()->get('data_adapter');

        $winners = $dataHandler->getWinners($input->getArgument('event_id'));

        $output->writeln("<info>List of winners:</info>");
        foreach ($winners as $winner) {
            $output->writeln("<comment>{$winner->winner->name}</comment> - <info>{$winner->prize}</info>");
        }
    }

    /**
     * @See Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('event_id')) {
            $eventId = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please insert the event ID: ',
                function ($eventId) {
                    if (empty($eventId)) {
                        throw new \Exception('The event ID can not be empty.');
                    }
                    return $eventId;
                }
            );
            $input->setArgument('event_id', $eventId);
        }
    }
}
