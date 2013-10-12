<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Raffle\Model;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class Event
{
    private $id;
    /**
     * @var array List of \Attendee
     */
    private $attendees = array();
    /**
     * @var array List of Prize
     */
    private $prizes = array();

    public function __construct($eventId)
    {
        $this->setId($eventId);
    }

    /**
     * Add Attendee to Event
     *
     * @param Attendee $attendee
     */
    public function addAttendee(Attendee $attendee)
    {
        $this->attendees[] = $attendee;
    }

    /**
     * Add Prize to Event
     *
     * @param Prize $prize
     */
    public function addPrize(Prize $prize)
    {
        $this->prizes[] = $prize;
    }

    /**
     * Converts Event properties to Json
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Converts Event properties to Array
     *
     * @return string
     */
    public function toArray()
    {
        return array(
            'event' => array(
                'id' => $this->getId(),
                'attendees' => $this->getAttendees(true),
                'prizes' => $this->getPrizes(true)
            )
        );
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $attendees
     */
    public function setAttendees(array $attendees)
    {
        $this->attendees = $attendees;
    }

    /**
     * @param array $prizes
     */
    public function setPrizes(array $prizes)
    {
        $this->prizes = $prizes;
    }


    /**
     * Gets Attendees
     *
     * @param bool $toArray
     * @return array The list of Attendees
     */
    public function getAttendees($toArray = false)
    {
        if ($toArray && isset($this->attendees)) {
            $attendees = array();
            foreach ($this->attendees as $attendee) {
                $attendees[] = $attendee->toArray();
            }
            return $attendees;
        }

        return $this->attendees;
    }

    /**
     * Gets Prizes
     *
     * @param bool $toArray
     * @return array The list of Prizes
     */
    public function getPrizes($toArray = false)
    {

        if ($toArray && isset($this->prizes)) {
            $prizes = array();
            foreach ($this->prizes as $prize) {
                $prizes[] = $prize->toArray();
            }
            return $prizes;
        }

        return $this->prizes;
    }

    /**
     * Gets the number of attendees
     *
     * @return int
     */
    public function getNumberOfAttendees()
    {
        return count($this->getAttendees());
    }

    /**
     * Gets the number of prizes
     *
     * @return int
     */
    public function getNumberOfPrizes()
    {
        return count($this->getPrizes());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * Check if exist attendees
     * @return bool
     */
    public function hasAttendees()
    {
        if (array() !== $this->attendees && count($this->attendees) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Clears the Attendees list
     */
    public function clearAttendees()
    {
        $this->attendees = array();
    }

    /**
     * Clears the Prizes list
     */
    public function clearPrizes()
    {
        $this->prizes = array();
    }

    /**
     * Check if exist prizes
     * @return bool
     */
    public function hasPrizes()
    {
        if (array() !== $this->prizes && count($this->prizes) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Gets a random Attendee and remove it from the Event
     *
     * @return Attendee The attendee
     * @throws \OutOfRangeException
     */
    public function popRandomAttendee()
    {
        if (0 === count($this->attendees)) {
            throw new \OutOfRangeException('The event does not have attendees.');
        }

        mt_srand(crc32(microtime(true)));
        $idx = mt_rand(0, count($this->attendees) - 1);
        $attendee = $this->attendees[$idx];

        unset($this->attendees[$idx]);

        // force array re-indexation
        $this->attendees = array_values($this->attendees);

        return $attendee;
    }

    /**
     * Remove a prize from Event
     *
     * @param int $index
     * @return Prize The prize
     */
    public function popPrize($index)
    {
        $prize = $this->prizes[$index];

        unset($this->prizes[$index]);

        // force array re-indexation
        $this->prizes = array_values($this->prizes);

        return $prize;
    }
} 