<?php
/*
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013-2014 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phplx\Raffle\Tests;

use Phplx\Raffle\Model\Attendee;
use Phplx\Raffle\Provider\ProviderInterface;

class MockProvider implements ProviderInterface
{
    public function getAttendees($eventId)
    {
        $attendee = new Attendee();
        $attendee->setId(1);
        $attendee->setEmail('me@danielcsgomes.com');
        $attendee->setName('Daniel Gomes');
        $attendee->setTwitterHandler('');

        return array($attendee);
    }
}

class MockProviderWithoutData implements ProviderInterface
{
    public function getAttendees($eventId)
    {
        return array();
    }
}
