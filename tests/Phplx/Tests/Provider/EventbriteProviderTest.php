<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) Daniel Gomes <me@danielcsgomes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Tests\Provider;

use EventbriteApiConnector\Eventbrite;
use Phplx\Provider\EventbriteProvider;

/**
 * @author Daniel Gomes <me@danielcsgomes.com>
 */
class EventbriteProviderTest extends \PHPUnit_Framework_TestCase 
{
    /**
     * @var EventbriteProvider
     */
    private $eventbriteProvider;
    /**
     * @var string
     */
    private $dummyContent;
    /**
     * @var Eventbrite
     */
    private $eventbrite;
    
    public function setUp()
    {
        if (!class_exists('EventbriteApiConnector\Eventbrite')) {
            $this->markTestSkipped('The EventbriteApiConnector library has to be installed');
        }
        
        $this->eventbrite = $this->getMock('EventbriteApiConnector\Eventbrite',array('post'),array(),'',false);
        
        $this->eventbriteProvider = new EventbriteProvider($this->eventbrite);
    }

    public function testGetAttendees()
    {
        $content = json_encode(
            array(
                 'attendees' => array(
                     array(
                         'attendee' => array(
                             'order_id' => 1,
                             'first_name' => 'Daniel',
                             'last_name' => 'Gomes',
                             'email' => 'me@danielcsgomes.com'
                         )
                     ),
                     array(
                         'attendee' => array(
                             'order_id' => 1,
                             'first_name' => 'Daniel',
                             'last_name' => 'Gomes',
                             'email' => 'me@danielcsgomes.com',
                             'answers' => array(
                                 array(
                                     'answer' => array(
                                         'question' => 'Twitter username',
                                         'answer_text' => 'danielcsgomes'
                                     )
                                 )
                             )
                         )
                     ),
                     array(
                         'attendee' => array(
                             'order_id' => 1,
                             'first_name' => 'Daniel',
                             'last_name' => 'Gomes',
                             'email' => 'me@danielcsgomes.com',
                             'answers' => array(
                                 array(
                                     'answer' => array(
                                         'question' => 'fake'
                                     )
                                 )
                             )
                         )
                     )
                 )
            )
        );

        $this->eventbrite->expects($this->once())
            ->method('post')
            ->will($this->returnValue($content));
            
        $attendees = $this->eventbriteProvider->getAttendees('test');
        
        $this->assertGreaterThan(0, count($attendees));
        $this->assertEquals('array', gettype($attendees));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetAttendeesWithEmptyList()
    {
        $content = json_encode(array());

        $this->eventbrite->expects($this->once())
            ->method('post')
            ->will($this->returnValue($content));

        $this->eventbriteProvider->getAttendees('test');
    }

    /**
     * @expectedException \Exception
     */
    public function testGetAttendeesWithMalformedJsonString()
    {
        $content = "malformed json";

        $this->eventbrite->expects($this->once())
            ->method('post')
            ->will($this->returnValue($content));

        $this->eventbriteProvider->getAttendees('test');
    }
}