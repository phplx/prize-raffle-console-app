<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Raffle\DataAdapter;

use Phplx\Raffle\Model\Event;
use Phplx\Raffle\Model\Prize;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
interface DataAdapterInterface
{
    /**
     * Verifies if the Event exists
     *
     * @param  string $eventId
     * @return bool
     */
    public function hasEvent($eventId);

    /**
     * Saves Event data
     *
     * @param  Event             $event
     * @return null
     * @throws \RuntimeException
     */
    public function saveEvent(Event $event);

    /**
     * Gets Event data
     *
     * @param  string            $eventId
     * @return Event
     * @throws \RuntimeException
     */
    public function getEvent($eventId);

    /**
     * Saves the prize winner with the prize information
     *
     * @param  string            $eventId
     * @param  Prize             $prize
     * @return null
     * @throws \RuntimeException
     */
    public function saveWinner($eventId, Prize $prize);

    /**
     * Gets the winners list
     *
     * @param  string            $eventId
     * @return array             List of Prize
     * @throws \RuntimeException
     */
    public function getWinners($eventId);

    /**
     * Clears the winners list
     *
     * @param  string            $eventId
     * @return bool
     * @throws \RuntimeException
     */
    public function clearWinners($eventId);
}
