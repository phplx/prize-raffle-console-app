<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class TweetCommand extends Command
{
    /**
     * @See Command
     */
    protected function configure()
    {
        $this
            ->setName('twitter:tweet')
            ->setDescription('Sends a tweet from @phplx account.')
            ->setDefinition(
                array(
                     new InputArgument('tweet_message', InputArgument::REQUIRED, 'The message of the tweet')
                )
            )
            ->setHelp(
                <<<EOT
                The <info>twitter:tweet</info> command send a tweet from @phplx account.
EOT
            );
    }

    /**
     * @See Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tweetMessage = $input->getArgument('tweet_message');
        $twitterHandler = $this->getApplication()->getContainer()->get('twitter_handler');
        $twitterHandler->tweet($tweetMessage);
    }

    /**
     * @See Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('tweet_message')) {
            $tweetMessage = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please insert the event ID:',
                function ($tweetMessage) {
                    if (empty($tweetMessage)) {
                        throw new \InvalidArgumentException('The tweet message can not be empty.');
                    }
                    return $tweetMessage;
                }
            );
            $input->setArgument('tweet_message', $tweetMessage);
        }
    }
}