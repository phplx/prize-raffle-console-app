<?php
/*
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013-2014 phplx.net
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
