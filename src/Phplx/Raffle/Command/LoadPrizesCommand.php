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
use Phplx\Raffle\Model\Prize;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class LoadPrizesCommand extends Command
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('meetup:prizes:load')
            ->setDescription('Loads prizes of an Event from an external file.')
            ->addArgument('event_id', InputArgument::REQUIRED, 'The event ID')
            ->addArgument('file', InputArgument::REQUIRED, 'The file to be loaded')
            ->setHelp(
                'The <info>meetup:prizes:load</info> command will load prizes to an Event from an external file.'
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataHandler = $this->getApplication()->getContainer()->get('data_adapter');

        $this->event = $dataHandler->getEvent($input->getArgument('event_id'));

        $doLoadPrizes = true;

        if ($this->event->hasPrizes()) {
            $doLoadPrizes = $this->getHelper('dialog')->askConfirmation(
                $output,
                'The list of prizes for this event ID already exists, do you want to override? (no) ',
                false
            );
        }

        if ($doLoadPrizes) {
            $this->event->clearPrizes();
            $this->loadPrizes($input->getArgument('file'));
            $dataHandler->saveEvent($this->event);

            $output->writeln(
                "<info>Loaded</info> <comment>{$this->event->getNumberOfPrizes()}</comment> <info>prizes.</info>"
            );
        }
    }

    /**
     * Gets the prizes content from a file
     *
     * @param  string            $file The file path
     * @throws \RuntimeException
     */
    private function loadPrizes($file)
    {
        try {
            $content = file_get_contents($file);
            $this->parsePrizes($content);
        } catch (\Exception $error) {
            throw new \RuntimeException($error->getMessage());
        }

    }

    /**
     * Parses the content into the Event.
     *
     * @param  string     $prizes
     * @throws \Exception
     */
    private function parsePrizes($prizes)
    {
        try {
            $prizes = json_decode($prizes);
            foreach ($prizes->prizes as $item) {
                $prize = new Prize();
                $prize->setSponsorName($item->sponsor);
                $prize->setPrizeTitle($item->prize);
                $prize->setTweetMessage($item->tweet_message);

                $this->event->addPrize($prize);
            }
        } catch (\Exception $error) {
            throw new \Exception($error->getMessage());
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
                        throw new \Exception('The event ID cannot be empty.');
                    }

                    return $eventId;
                }
            );
            $input->setArgument('event_id', $eventId);
        }

        if (!$input->getArgument('file')) {
            $file = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please insert the file name within the full path. Only JSON format is allowed: ',
                function ($file) {
                    if (!file_exists($file)) {
                        throw new \Exception('The file does not exist.');
                    }

                    $fileExtension = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
                    if ('JSON' !== $fileExtension) {
                        throw new \Exception('The file extension is not allowed, only JSON is allowed.');
                    }

                    return $file;
                }
            );
            $input->setArgument('file', $file);
        }
    }
}
