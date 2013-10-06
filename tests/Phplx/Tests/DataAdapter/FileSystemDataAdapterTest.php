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

use Phplx\DataAdapter\FileSystemDataAdapter;

/**
 * @author Daniel Gomes <me@danielcsgomes.com>
 */
class FileSystemDataAdapterTest extends \PHPUnit_Framework_TestCase 
{
    /**
     * @var FileSystemDataAdapter
     */
    private $fsDataAdapter;
    
    public function setUp()
    {
        $this->fsDataAdapter = new FileSystemDataAdapter();
        $this->fsDataAdapter->setBaseDir(sys_get_temp_dir());
    }

    public function testSaveEvent()
    {
        $eventId = 'testSaveEvent';

        $jsonResponse = json_encode(
            array(
                 'event' => array(
                     'id' => $eventId,
                     'attendees' => array(
                         array(
                             'id' => 1,
                             'name' => 'Daniel Gomes',
                             'email' => 'me@danielcsgomes.com'
                         ),
                         array(
                             'id' => 1,
                             'name' => 'Daniel Gomes',
                             'email' => 'me@danielcsgomes.com',
                             'twitterHandler' => 'danielcsgomes'
                         )
                     ),
                     'prizes' => array(
                         array(
                             'sponsor' => 'phplx',
                             'prize' => 'prize',
                             'tweet_message' => 'phplx winner',
                             'winner' => array(
                                 'id' => 1
                             )
                         )
                     )
                 )
            )
        );

        $mock = $this->getMock('Phplx\Model\Event', array(), array('test'), '', false);
        $mock->expects($this->atLeastOnce())
            ->method('toJson')
            ->will($this->returnValue($jsonResponse));
        $mock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($eventId));

        $this->fsDataAdapter->saveEvent($mock);

        return $eventId;
    }

    /**
     * @depends testSaveEvent
     */
    public function testGetExistingEvent($eventId)
    {
        $this->assertInstanceOf('Phplx\Model\Event', $this->fsDataAdapter->getEvent('testSaveEvent'));

        return $eventId;
    }

    public function testGetNonExistingEvent()
    {
        $this->assertInstanceOf('Phplx\Model\Event', $this->fsDataAdapter->getEvent('nonExistingEvent'));
    }

    /**
     * @depends testGetExistingEvent
     */
    public function testDeleteAnExistingEvent($eventId)
    {
        $this->assertTrue($this->fsDataAdapter->deleteEvent($eventId));
    }

    public function testHasEvent()
    {
        $tmpFile = sys_get_temp_dir() . '/event.json';
        file_put_contents($tmpFile, '');
        
        $this->assertTrue($this->fsDataAdapter->hasEvent('event'));
        unlink($tmpFile);
    }

    public function testNotHasEvent()
    {
        $this->assertFalse($this->fsDataAdapter->hasEvent('file_not_exists'));
    }

    public function testSaveWinner()
    {
        $mock = $this->getMock('Phplx\Model\Prize');
        $mock->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue('winner'));

        $this->fsDataAdapter->saveWinner('testSaveWinner', $mock);

        $filename = "{$this->fsDataAdapter->getBaseDir()}/testSaveWinner_winners.json";

        $this->assertFileExists($filename);
        $this->assertJson(file_get_contents($filename));

        return $filename;
    }

    /**
     * @depends testSaveWinner
     */
    public function testSaveWinnerWithExistingFile($filename)
    {
        $mock = $this->getMock('Phplx\Model\Prize');
        $mock->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue('winner'));

        $this->fsDataAdapter->saveWinner('testSaveWinner', $mock);

        $this->assertFileExists($filename);
        $this->assertJson(file_get_contents($filename));

        return $filename;
    }

    /**
     * @depends testSaveWinnerWithExistingFile
     */
    public function testGetWinners($filename)
    {
        $this->assertInternalType('array', $this->fsDataAdapter->getWinners('testSaveWinner'));
        unlink($filename);
    }
}   
