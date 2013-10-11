<?php

namespace Phplx\Tests\Command;

use Phplx\Command\TweetCommand;
use Symfony\Component\Console\Tester\CommandTester;

class TweetCommandTest extends BaseCommandTest
{
    public function testTweetAMessage()
    {
        $this->application->add(new TweetCommand());

        $tweet_message = 'test_tweet';

        $command = $this->application->find('twitter:tweet');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                 'command' => $command->getName(),
                 'tweet_message' => $tweet_message
            )
        );

        $this->assertContains('Tweet sent successfully', $commandTester->getDisplay());
    }
}
