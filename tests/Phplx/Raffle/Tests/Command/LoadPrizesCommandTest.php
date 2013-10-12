<?php

namespace Phplx\Raffle\Tests\Command;

use Phplx\Raffle\Command\LoadPrizesCommand;
use Symfony\Component\Console\Tester\CommandTester;

class LoadPrizesCommandTest extends BaseCommandTest
{
    public function testLoadPrizesWithEventWithoutPrizes()
    {
        $this->application->add(new LoadPrizesCommand());

        $command = $this->application->find('meetup:prizes:load');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'event_id' => 'test',
                 'file' => __DIR__ . '/../Fixtures/prizes.json'
            )
        );

        $this->assertContains('Loaded 2 prizes', $commandTester->getDisplay());
    }

    /**
     * @depends testLoadPrizesWithEventWithoutPrizes
     */
    public function testOverrideLoadPrizes()
    {
        $this->application->add(new LoadPrizesCommand());

        $command = $this->application->find('meetup:prizes:load');

        // We mock the DialogHelper
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        $dialog->expects($this->at(0))
            ->method('askConfirmation')
            ->will($this->returnValue(true));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);

        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'event_id' => 'test',
                 'file' => __DIR__ . '/../Fixtures/prizes.json'
            )
        );

        $this->assertContains('Loaded 2 prizes', $commandTester->getDisplay());

        // remove the event file from cache
        unlink($this->cacheDir . '/test.json');
    }
}
