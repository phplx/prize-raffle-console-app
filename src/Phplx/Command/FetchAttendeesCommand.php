<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Command;

use Phplx\Model\Event;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class FetchAttendeesCommand extends Command
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
            ->setName('meetup:attendees:get')
            ->setDescription('Gets the meetup attendees list.')
            ->setDefinition(
                array(
                     new InputArgument('event_id', InputArgument::REQUIRED, 'The event ID'),
                )
            )
            ->setHelp(
                <<<EOT
                The <info>meetup:attendees:get</info> command will get all attendees for an event ID from EventBrite.
EOT
            );
    }

    /**
     * @See Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getContainer();
        $dataHandler = $container->get('data_adapter');

        $this->event = $dataHandler->getEvent($input->getArgument('event_id'));

        $doGetAttendees = true;

        if ($this->event->hasAttendees()) {
            $doGetAttendees = $this->getHelper('dialog')->askConfirmation(
                $output,
                'The list of attendees for this event ID already exists, do you want to override? (no) ',
                false
            );
        }

        if ($doGetAttendees) {
            $this->event->clearAttendees();
            $attendees = $container->get('provider')->getAttendees((string)$this->event);
            $this->event->setAttendees($attendees);
            $dataHandler->saveEvent($this->event);
            $output->writeln(
                "<info>Total attendees:</info><comment>{$this->event->getNumberOfAttendees()}</comment>"
            );
        }

        $output->writeln("<info>You can now start the prizes raffle!</info>");
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