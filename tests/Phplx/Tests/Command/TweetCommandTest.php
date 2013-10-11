<?php

namespace Phplx\Tests\Command;

use Phplx\Command\TweetCommand;
use Symfony\Component\Console\Tester\CommandTester;

class TweetCommandTest extends BaseCommandTest
{
    public function testTweetAMessage()
    {
        // Set the TwitterSocialHandler Mock
        $this->application->getContainer()->setParameter(
            'twitter_social_handler.class',
            'Phplx\Tests\MockTwitterSocialHandler'
        );

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

    public function testFailingToTweetAMessage()
    {
        // Set the TwitterSocialHandler Mock
        $this->application->getContainer()->setParameter(
            'twitter_social_handler.class',
            'Phplx\Tests\MockTwitterSocialHandlerFailingTweet'
        );

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

        $this->assertContains('An error occur when sending the Tweet.', $commandTester->getDisplay());
    }
}