<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\SocialHandler;

use OAuth;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class TwitterSocialHandler
{

    /**
     * @var OAuth
     */
    private $oauth;

    /**
     * @param $consumerKey
     * @param $consumerSecret
     * @param $accessToken
     * @param $accessTokenSecret
     */
    public function __construct($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret)
    {
        $this->oauth = new OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $this->oauth->setToken($accessToken, $accessTokenSecret);
    }

    /**
     * Sends a tweet
     *
     * @param $message The tweet message
     */
    public function tweet($message)
    {
        $this->oauth->fetch(
            'https://api.twitter.com/1.1/statuses/update.json',
            array('status' => $message),
            'POST'
        );
    }

}