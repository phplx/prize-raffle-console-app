<?php
/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) Daniel Gomes <me@danielcsgomes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phplx\Raffle\Tests;

use Phplx\Raffle\DataAdapter\DataAdapterInterface;
use Phplx\Raffle\Model\Event;
use Phplx\Raffle\Model\Prize;

class MockDataAdapter implements DataAdapterInterface
{
    /**
     * @var Event
     */
    private $event;

    public function hasEvent($eventId)
    {
        return isset($eventId);
    }

    public function saveEvent(Event $event)
    {
    }

    public function getEvent($eventId)
    {
        if (!isset($this->event)) {
            $this->event = new Event($eventId);
        }

        return $this->event;
    }

    public function saveWinner($eventId, Prize $prize)
    {
    }

    public function getWinners($eventId)
    {
    }

    public function clearWinners($eventId)
    {
    }
}
