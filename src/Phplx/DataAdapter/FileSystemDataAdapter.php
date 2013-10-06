<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\DataAdapter;

use Phplx\Model\Attendee;
use Phplx\Model\Event;
use Phplx\Model\Prize;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class FileSystemDataAdapter implements DataAdapterInterface
{
    private $baseDir;

    public function __construct()
    {
        $this->baseDir = __DIR__ . '/../../../cache';
    }

    /**
     * Saves Event data
     *
     * @param Event $event
     * @return null
     * @throws \RuntimeException
     */
    public function saveEvent(Event $event)
    {
        $filename = "{$this->baseDir}/{$event->getId()}.json";
        $saved = file_put_contents($filename, $event->toJson(), LOCK_EX);

        if (false === $saved) {
            throw new \RuntimeException("Failed to write on the file {$event->getId()}.json");
        }
    }

    /**
     * Gets Event data
     *
     * @param  string $eventId
     * @return Event
     * @throws \RuntimeException
     */
    public function getEvent($eventId)
    {
        if ($this->hasEvent($eventId)) {
            $data = file_get_contents("{$this->baseDir}/{$eventId}.json");

            if (false === $data) {
                throw new \RuntimeException("Failed to open the file {$eventId}.json");
            }

            return $this->parseEventFromJson($data);
        }

        return new Event($eventId);
    }

    /**
     * Verifies if the Event exists
     *
     * @param string $eventId
     * @return bool
     */
    public function hasEvent($eventId)
    {
        if (file_exists("{$this->baseDir}/{$eventId}.json")) {
            return true;
        }

        return false;
    }

    /**
     * Parses the json data into an Event object
     *
     * @param $data
     *
     * @return Event
     */
    private function parseEventFromJson($data)
    {
        $dataObj = json_decode($data);

        $event = new Event($dataObj->event->id);

        if (isset($dataObj->event->attendees)) {
            // cleans the array
            $attendees = array_filter($dataObj->event->attendees);

            foreach ($attendees as $item) {
                $attendee = new Attendee();
                $attendee->setId($item->id);
                $attendee->setName($item->name);
                $attendee->setEmail($item->email);

                if (isset($item->twitterHandler) && !empty($item->twitterHandler)) {
                    $attendee->setTwitterHandler($item->twitterHandler);
                }

                $event->addAttendee($attendee);
            }
        }

        if (isset($dataObj->event->prizes)) {
            // cleans the array
            $prizes = array_filter($dataObj->event->prizes);

            foreach ($prizes as $item) {
                $prize = new Prize();
                $prize->setSponsorName($item->sponsor);
                $prize->setPrizeTitle($item->prize);

                if (isset($item->winner)) {
                    $attendee = new Attendee();
                    $attendee->setId($item->winner->id);
                    $prize->setWinner($attendee);
                }

                if (isset($item->tweet_message)) {
                    $prize->setTweetMessage($item->tweet_message);
                }

                $event->addPrize($prize);
            }
        }

        return $event;
    }

    /**
     * Saves the prize winner with the prize information
     *
     * @param string $eventId
     * @param Prize $prize
     * @return null
     * @throws \RuntimeException
     */
    public function saveWinner($eventId, Prize $prize)
    {
        $filename = "{$this->baseDir}/{$eventId}_winners.json";

        $prizes = array();

        if (file_exists($filename)) {
            $contents = file_get_contents($filename);
            $prizes = json_decode($contents);
        }

        $prizes[] = $prize->toArray();

        $saved = file_put_contents($filename, json_encode($prizes), LOCK_EX);

        if (false === $saved) {
            throw new \RuntimeException("Failed to write on the file {$eventId}_winners.json");
        }
    }

    /**
     * Gets the winners list
     *
     * @param  string $eventId
     * @return array List of Prize
     * @throws \RuntimeException
     */
    public function getWinners($eventId)
    {
        $data = file_get_contents("{$this->baseDir}/{$eventId}_winners.json");

        if (false === $data) {
            throw new \RuntimeException("Failed to open the file {$eventId}_winners.json");
        }

        return json_decode($data);
    }

    /**
     * @param string $eventId The event Id
     * @return bool true if deleted with success
     */
    public function deleteEvent($eventId)
    {
        return unlink("{$this->getBaseDir()}/{$eventId}.json");
    }

    /**
     * @param string $baseDir
     */
    public function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }
} 