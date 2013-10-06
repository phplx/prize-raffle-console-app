<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Model;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class Prize
{
    /**
     * @var string
     */
    private $sponsorName;
    /**
     * @var string
     */
    private $prizeTitle;
    /**
     * @var string
     */
    private $tweetMessage;
    /**
     * @var Attendee
     */
    private $winner;

    /**
     * @return string
     */
    public function getTweetMessage()
    {
        return $this->tweetMessage;
    }

    /**
     * @param string $tweetMessage
     */
    public function setTweetMessage($tweetMessage)
    {
        $this->tweetMessage = $tweetMessage;
    }

    /**
     * Converts Prize properties to Array
     *
     * return array
     */
    public function toArray()
    {
        $output = array(
            'sponsor' => $this->getSponsorName(),
            'prize' => $this->getPrizeTitle(),
            'tweet_message' => $this->getTweetMessage(),
        );

        if (null !== $this->getWinner()) {
            $output['winner'] = $this->winner->toArray();
        }

        return $output;
    }

    /**
     * @return string
     */
    public function getSponsorName()
    {
        return $this->sponsorName;
    }

    /**
     * @param string $sponsorName
     */
    public function setSponsorName($sponsorName)
    {
        $this->sponsorName = $sponsorName;
    }

    /**
     * @return string
     */
    public function getPrizeTitle()
    {
        return $this->prizeTitle;
    }

    /**
     * @param string $prizeTitle
     */
    public function setPrizeTitle($prizeTitle)
    {
        $this->prizeTitle = $prizeTitle;
    }

    /**
     * @return Attendee
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param Attendee $winner
     */
    public function setWinner(Attendee $winner)
    {
        $this->setTweetMessage(
            sprintf($this->tweetMessage, $winner->getTweetName())
        );
        $this->winner = $winner;
    }

    /**
     * Check if the Prize already has a winner
     *
     * @return bool
     */
    public function hasWinner()
    {
        if (isset($this->winner)) {
            return true;
        }

        return false;
    }

    public function __toString()
    {
        return $this->getSponsorName() . ' - ' . $this->getPrizeTitle();
    }
} 