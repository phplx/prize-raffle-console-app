<?php

namespace Phplx\Raffle\Tests\Command;

use Phplx\Raffle\Command\ClearWinnersListCommand;
use Symfony\Component\Console\Tester\CommandTester;

class ClearWinnersListCommandTest extends BaseCommandTest
{
    /**
     * @var ClearWinnersListCommand
     */
    private $command;

    public function setUp()
    {
        parent::setUp();

        $this->application->add(new ClearWinnersListCommand());
        $this->command = $this->application->find('meetup:winners:clear');
    }

    public function testClearTheWinnersList()
    {
        $content = 'Winner testing';
        $filename = $this->cacheDir . '/test_winners.json';

        file_put_contents($filename, $content);

        $this->assertFileExists($filename);
        $this->assertEquals(file_get_contents($filename), $content);

        // Mock the DialogHelper
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        // Reply true to "Save Winner"
        $dialog->expects($this->atLeastOnce())
            ->method('askConfirmation')
            ->will($this->returnValue(true));

        // We override the standard helper with our mock
        $this->command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            array(
                 'command' => $this->command->getName(),
                 'event_id' => 'test'
            )
        );

        $this->assertEquals('', file_get_contents($filename));
        $this->assertContains('List of winners is now cleared.', $commandTester->getDisplay());

        unlink($filename);
    }

    public function testClearWinnersWithoutTheResource()
    {
        // Mock the DialogHelper
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        // Reply true to "Save Winner"
        $dialog->expects($this->atLeastOnce())
            ->method('askConfirmation')
            ->will($this->returnValue(true));

        // We override the standard helper with our mock
        $this->command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            array(
                 'command' => $this->command->getName(),
                 'event_id' => 'test'
            )
        );

        $this->assertContains('File does not exists', $commandTester->getDisplay());
    }

    public function testCancelClearTheWinnersList()
    {
        $filename = $this->cacheDir . '/test_winners.json';

        file_put_contents($filename, 'a');

        // Mock the DialogHelper
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        // Reply true to "Save Winner"
        $dialog->expects($this->atLeastOnce())
            ->method('askConfirmation')
            ->will($this->returnValue(false));

        // We override the standard helper with our mock
        $this->command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            array(
                 'command' => $this->command->getName(),
                 'event_id' => 'test'
            )
        );

        $this->assertContains('The clear winners list command was cancelled.', $commandTester->getDisplay());

        unlink($filename);
    }
}
