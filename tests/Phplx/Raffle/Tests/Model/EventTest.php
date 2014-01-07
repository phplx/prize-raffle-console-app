<?php

/*
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013-2014 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phplx\Raffle\Tests\Model;

use Phplx\Raffle\Model\Event;

/**
 * @author Daniel Gomes <me@danielcsgomes.com>
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyAttendeesList()
    {
        $event = new Event('test');
        $this->assertFalse($event->hasAttendees());
        $this->assertEquals(array(), $event->getAttendees());
        $this->assertEquals(array(), $event->getAttendees(true));
        $this->assertEquals(0, $event->getNumberOfAttendees());
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testPopAttendeeWithoutAttendees()
    {
        $event = new Event('test');
        $event->popRandomAttendee();
    }

    public function testPopAttendee()
    {
        $event = new Event('test');
        $event->setAttendees(
            array(
                 'Daniel'
            )
        );
        $this->assertEquals(1, $event->getNumberOfAttendees());
        $this->assertTrue($event->hasAttendees());
        $this->assertEquals('Daniel', $event->popRandomAttendee());
    }

    public function testEmptyPrizesList()
    {
        $event = new Event('test');
        $this->assertFalse($event->hasPrizes());
        $this->assertEquals(array(), $event->getPrizes());
        $this->assertEquals(array(), $event->getPrizes(true));
        $this->assertEquals(0, $event->getNumberOfPrizes());
    }

    public function testPopPrize()
    {
        $event = new Event('test');
        $event->setPrizes(
            array(
                 'Prize'
            )
        );
        $this->assertEquals(1, $event->getNumberOfPrizes());
        $this->assertTrue($event->hasPrizes());
        $this->assertEquals('Prize', $event->popPrize(0));
    }

    public function testResetPrizeAndAttendees()
    {
        $event = new Event('test');
        $event->setPrizes(array('Prize'));
        $event->setAttendees(array('Daniel'));

        $this->assertEquals(1, $event->getNumberOfPrizes());
        $this->assertEquals(1, $event->getNumberOfAttendees());

        $event->clearAttendees();
        $event->clearPrizes();

        $this->assertEquals(0, $event->getNumberOfPrizes());
        $this->assertEquals(0, $event->getNumberOfAttendees());
    }

    public function testConvertToArrayAndJson()
    {
        $event = new Event('test');
        $expected = array(
            'event' => array(
                'id' => (string) $event,
                'attendees' => $event->getAttendees(true),
                'prizes' => $event->getPrizes(true)
            )
        );

        $this->assertEquals($expected, $event->toArray());
        $this->assertEquals(json_encode($expected), $event->toJson());
    }
}
