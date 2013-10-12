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

use Phplx\Raffle\Model\Event;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class ListPrizesCommand extends Command
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @See Command
     */
    protected function configure()
    {
        $this
            ->setName('meetup:prizes:list')
            ->setDescription('Lists the prizes of an Event.')
            ->addArgument('event_id', InputArgument::REQUIRED, 'The event ID')
            ->setHelp('The <info>meetup:prizes:list</info> command will list the prize of an event.');
    }

    /**
     * @See Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataHandler = $this->getApplication()->getContainer()->get('data_adapter');

        $this->event = $dataHandler->getEvent($input->getArgument('event_id'));

        if (!$this->event->hasPrizes()) {
            throw new \Exception('This event has no prizes setted.');
        }

        $output->writeln("<info>List of prizes:</info>");
        foreach ($this->event->getPrizes() as $prize) {
            $output->writeln("<comment>{$prize->getSponsorName()} - {$prize->getPrizeTitle()}</comment>");
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