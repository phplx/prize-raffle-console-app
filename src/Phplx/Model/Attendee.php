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
class Attendee
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $twitterHandler;

    /**
     * Converts Attendee properties to Array
     *
     * return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'twitterHandler' => $this->twitterHandler
        );
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $twitterHandler
     */
    public function setTwitterHandler($twitterHandler)
    {
        $this->twitterHandler = str_replace('@', '', $twitterHandler);
    }

    /**
     * Gets the Twitter Handler if exists or the attendee Name
     *
     * @return string
     */
    public function getTweetName()
    {
        if (isset($this->twitterHandler)) {
            return '@' . $this->twitterHandler;
        }

        return $this->name;
    }
} 