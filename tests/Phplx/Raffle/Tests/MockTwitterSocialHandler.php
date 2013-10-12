<?php
/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) Daniel Gomes <me@danielcsgomes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phplx\Raffle\Tests;

class MockTwitterSocialHandler
{
    public function tweet($message)
    {
        return $message;
    }
}

class MockTwitterSocialHandlerFailingTweet
{
    public function tweet($message)
    {
        return false;
    }
}