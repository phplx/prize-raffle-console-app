<?php

namespace Phplx\Tests\Command;

use Phplx\Command\ListPrizesCommand;
use Symfony\Component\Console\Tester\CommandTester;

class ListPrizesCommandTest extends BaseCommandTest
{
    public function testListPrizesWithPrizes()
    {
        copy(__DIR__ . '/../Fixtures/testEventWithPrizesAndAttendees.json', $this->cacheDir . '/test.json');

        $this->application->add(new ListPrizesCommand());

        $command = $this->application->find('meetup:prizes:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'event_id' => 'test'
            )
        );

        $this->assertContains('Sponsor Name - Prize Name', $commandTester->getDisplay());

        unlink($this->cacheDir . '/test.json');
    }

    /**
     * @expectedException \Exception
     */
    public function testListPrizesWithoutEvent()
    {
        $this->application->add(new ListPrizesCommand());

        $command = $this->application->find('meetup:prizes:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'event_id' => 'test'
            )
        );
    }
}
