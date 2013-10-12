<?php

namespace Phplx\Raffle\Tests\Command;

use Phplx\Raffle\Command\ListWinnersCommand;
use Symfony\Component\Console\Tester\CommandTester;

class ListWinnersCommandTest extends BaseCommandTest
{
    public function testListWinners()
    {
        copy(__DIR__ . '/../Fixtures/test_winners.json', $this->cacheDir . '/test_winners.json');

        $this->application->add(new ListWinnersCommand());

        $command = $this->application->find('meetup:prizes:winners');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'event_id' => 'test'
            )
        );

        $this->assertContains('Daniel Gomes - Prize Name', $commandTester->getDisplay(), '', true);

        unlink($this->cacheDir . '/test_winners.json');
    }

    /**
     * @expectedException \Exception
     */
    public function testListPrizesWithoutFile()
    {
        $this->application->add(new ListWinnersCommand());

        $command = $this->application->find('meetup:prizes:winners');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'event_id' => 'test'
            )
        );
    }
}
