<?php
/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) Daniel Gomes <me@danielcsgomes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phplx\Raffle\Provider;


interface ProviderInterface
{
    /**
     * Fetch the attendees from a specific event
     *
     * @param string $eventId
     * @return array A list of \Phplx\Raffle\Model\Attendee
     */
    public function getAttendees($eventId);
} 