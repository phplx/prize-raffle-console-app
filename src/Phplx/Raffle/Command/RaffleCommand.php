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

use Phplx\Raffle\DataAdapter\DataAdapterInterface;
use Phplx\Raffle\Exception\EventNotFoundException;
use Phplx\Raffle\Model\Attendee;
use Phplx\Raffle\Model\Event;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class RaffleCommand extends Command
{
    /**
     * @var Event
     */
    private $event;
    /**
     * @var DataAdapterInterface
     */
    private $dataHandler;

    /**
     * @See Command
     */
    protected function configure()
    {
        $this
            ->setName('meetup:raffle')
            ->setDescription('Starts the Prize Raffle.')
            ->addArgument('event_id', InputArgument::REQUIRED, 'The event ID')
            ->setHelp('The <info>meetup:raffle</info> command starts the Prize Raffle of an Event.');
    }

    /**
     * @See Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dataHandler = $this->getApplication()->getContainer()->get('data_adapter');
        $eventId = $input->getArgument('event_id');

        if (!$this->dataHandler->hasEvent($eventId)) {
            throw new EventNotFoundException("The event does not exist. Fetch the Attendees and add prizes before start Raffling.");
        }

        $this->event = $this->dataHandler->getEvent($eventId);

        $this->doRaffle($output);
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
                        throw new \InvalidArgumentException('The event ID can not be empty.');
                    }
                    return $eventId;
                }
            );
            $input->setArgument('event_id', $eventId);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function doRaffle(OutputInterface $output)
    {
        while ($prizes = $this->event->getPrizes()) {
            $prize = $this->event->popPrize($this->selectPrize($prizes, $output));

            $winner = $this->getRandomAttendee($output, $prize);

            $prize->setWinner($winner);
            $this->dataHandler->saveWinner($this->event->getId(), $prize);
            $this->dataHandler->saveEvent($this->event);

            $this->tweetWinner($output, $prize);
        }
    }

    /**
     * @param OutputInterface $output
     * @param $prize
     */
    private function tweetWinner(OutputInterface $output, $prize)
    {
        if ($this->isToTweet($output, $prize->getTweetMessage())) {
            $tweetCmd = $this->getApplication()->find('twitter:tweet');
            $arguments = array(
                'command' => 'twitter:tweet',
                'tweet_message' => $prize->getTweetMessage()
            );

            $input = new ArrayInput($arguments);
            $tweetCmd->run($input, $output);
        }
    }

    /**
     * @param OutputInterface $output
     * @param string $prize The prize name
     * @return Attendee
     * @throws \Phplx\Raffle\Exception\WinnerNotFoundException
     */
    private function getRandomAttendee(OutputInterface $output, $prize)
    {
        $isWinner = false;

        while (!$isWinner) {
            $attendee = $this->event->popRandomAttendee();

            $this->dataHandler->saveEvent($this->event);

            $output->writeln(
                sprintf(
                    "<comment>The winner of the</comment> <info>%s</info> <comment>prize is</comment> <info>%s - %s</info>",
                    $prize,
                    $attendee->getName(),
                    $attendee->getEmail()
                )
            );

            $isWinner = $this->getHelper('dialog')->askConfirmation(
                $output,
                'Save the Winner? (yes/no) ',
                false
            );
        }

        return $attendee;
    }

    /**
     * @param OutputInterface $output
     * @param $tweetMessage
     * @return mixed
     */
    private function isToTweet(OutputInterface $output, $tweetMessage)
    {
        return $this->getHelper('dialog')->askConfirmation(
            $output,
            "Do you want to send this tweet: \"{$tweetMessage}\" ? (no) ",
            false
        );
    }

    /**
     * @param $prizesList
     * @param $output
     * @return mixed
     */
    private function selectPrize($prizesList, $output)
    {
        $prizes = array();
        foreach ($prizesList as $key => $prize) {
            $prizes[$key] = (string)$prize;
        }

        return $this->getHelper('dialog')->select(
            $output,
            'Choose the prize to raffle: ',
            $prizes,
            0
        );
    }
}
