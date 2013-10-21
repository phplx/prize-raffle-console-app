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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class ClearWinnersListCommand extends Command
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('meetup:winners:clear')
            ->setDescription('Clear the list of winners of an Event.')
            ->addArgument('event_id', InputArgument::REQUIRED, 'The event ID')
            ->setHelp(
                'The <info>meetup:winners:clear</info> command will remove all winners of an event.'
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataHandler = $this->getApplication()->getContainer()->get('data_adapter');

        $answer = $this->getHelper('dialog')->askConfirmation(
            $output,
            'Do you want to remove all winners? <info>[no]</info> ',
            false
        );

        if (!$answer) {
            $output->writeln('<info>The clear winners list command was cancelled.</info>');

            return;
        }

        try {
            $dataHandler->clearWinners($input->getArgument('event_id'));
            $output->writeln('<info>List of winners is now cleared.</info>');
        } catch (\RuntimeException $error) {
            $output->writeln(sprintf('<error>%s</error>', $error->getMessage()));
        }
    }

    /**
     * @see Command
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
