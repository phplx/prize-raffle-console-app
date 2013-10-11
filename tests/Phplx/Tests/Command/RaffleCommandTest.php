<?php

namespace Phplx\Tests\Command;

use Phplx\Command\RaffleCommand;
use Phplx\Command\TweetCommand;
use Symfony\Component\Console\Tester\CommandTester;

class RaffleCommandTest extends BaseCommandTest
{
    public function testRaffleAPrizeWithoutTweeting()
    {
        copy(__DIR__ . '/../Fixtures/testRaffle.json', $this->cacheDir . '/test.json');

        $this->application->add(new RaffleCommand());

        $command = $this->application->find('meetup:raffle');

        // Mock the DialogHelper
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation', 'select'));
        // Reply true to "Save Winner"
        $dialog->expects($this->at(0))
            ->method('askConfirmation')
            ->will($this->returnValue('yes'));
        // Reply false to "Tweet the Winner"
        $dialog->expects($this->at(1))
            ->method('askConfirmation')
            ->will($this->returnValue('no'));
        // Selects the first prize
        $dialog->expects($this->once())
            ->method('select')
            ->will($this->returnValue(0));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'event_id' => 'test'
            )
        );

        $this->assertContains('The winner of', $commandTester->getDisplay());
    }

    public function testRaffleAPrizeAndTweetTheWinner()
    {
        // Set the TwitterSocialHandler Mock
        $this->application->getContainer()->setParameter(
            'twitter_social_handler.class',
            'Phplx\Tests\MockTwitterSocialHandler'
        );

        copy(__DIR__ . '/../Fixtures/testRaffle.json', $this->cacheDir . '/test.json');

        $this->application->add(new RaffleCommand());
        $this->application->add(new TweetCommand());

        $command = $this->application->find('meetup:raffle');

        // Mock the DialogHelper
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation', 'select'));
        $dialog->expects($this->atLeastOnce())
            ->method('askConfirmation')
            ->will($this->returnValue('yes'));
        $dialog->expects($this->once())
            ->method('select')
            ->will($this->returnValue(0));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'event_id' => 'test'
            )
        );

        $this->assertContains('The winner of', $commandTester->getDisplay());
        $this->assertContains('Tweet sent successfully', $commandTester->getDisplay());
    }

    public function tearDown()
    {
        // remove the event files from cache
        unlink($this->cacheDir . '/test.json');
        unlink($this->cacheDir . '/test_winners.json');
    }
}