<?php

/**
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013 phplx.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phplx\Raffle\Provider;

use EventbriteApiConnector\Eventbrite;
use Phplx\Raffle\Model\Attendee;

/**
 * @author  Daniel Gomes <me@danielcsgomes.com>
 */
class EventbriteProvider implements ProviderInterface
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
     * @return array A list of \Phplx\Raffle\Model\Attendee
     */
    public function getAttendees($eventId)
    {
        $content = $this->eventbrite->post('event_list_attendees', array('event_id' => $eventId));

        return $this->parseAttendees($content);
    }

    /**
     * @param string $json The response content must be a valid json string
     * @return array A list of \Phplx\Raffle\Model\Attendee
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
                $attendee->setTwitterHandler($this->getTwitterUsername($person->attendee));

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

    /**
     * Get the Twitter username
     *
     * @param \stdClass $attendee
     * @return null
     *
     * @TODO - Make the twitter username question maps dynamically without knowing the question text, probably with DI parameter
     */
    private function getTwitterUsername(\stdClass $attendee)
    {
        if (!isset($attendee->answers) || empty($attendee->answers)) {
            return null;
        }

        if ("Twitter username" === $attendee->answers[0]->answer->question) {
            return $attendee->answers[0]->answer->answer_text;
        }

        return null;
    }
}
