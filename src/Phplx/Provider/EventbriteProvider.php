<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Provider;

use EventbriteApiConnector\Eventbrite;
use Phplx\Model\Attendee;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class EventbriteProvider
{
    /**
     * @var Eventbrite
     */
    private $eventbrite;

    public function __construct(Eventbrite $eventbrite)
    {
        $this->eventbrite = $eventbrite;
    }

    /**
     * Gets the attendees of a specific event.
     *
     * @param string $eventId The event Id
     *
     * @return array A list of Phplx\Model\Attendee
     */
    public function getAttendees($eventId)
    {
        $content = $this->eventbrite->post('event_list_attendees', array('event_id' => $eventId));

        return $this->parseAttendees($content);
    }

    /**
     * @param string $json The response content must be a valid json string
     * @return array A list of Phplx\Model\Attendee
     * @throws \Exception
     */
    private function parseAttendees($json)
    {
        try {
            $data = json_decode($json);

            $attendees = array();

            foreach ($data->attendees as $person) {
                $attendee = new Attendee();
                $attendee->setId($person->attendee->order_id);
                $attendee->setName($person->attendee->first_name . ' ' . $person->attendee->last_name);
                $attendee->setEmail($person->attendee->email);

                $attendees[] = $attendee;
            }

            return $attendees;
        } catch (\Exception $error) {
            if ($jsonError = json_last_error()) {
                throw new \Exception((string)$jsonError);
            }

            throw new \Exception('Unable to parse Attendees');
        }
    }

} 