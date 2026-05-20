<?php
require_once APPPATH . '../vendor/autoload.php'; // Adjust if needed

class GoogleMeet {
    private $client;
    private $service;

    public function __construct() {
        $credentials = [
            "client_id" => settings()->google_client_id,
            "client_secret" => settings()->google_client_secret,
            "redirect_uris" => [base_url().'meeting/callback']
        ];

        $this->client = new Google_Client();
        $this->client->setClientId($credentials['client_id']);
        $this->client->setClientSecret($credentials['client_secret']);
        $this->client->setRedirectUri($credentials['redirect_uri']);
        $this->client->setScopes([Google_Service_Calendar::CALENDAR_EVENTS]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        $this->service = new Google_Service_Calendar($this->client);
    }

    public function authenticate($code = null) {
        if ($code) {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            if (!isset($token['error'])) {
                $_SESSION['access_token'] = $token;
                return true;
            }
        } else {
            return $this->client->createAuthUrl();
        }
        return false;
    }

    public function createMeeting($title, $startTime, $endTime, $timezone = 'Asia/Dhaka') {
        $this->client->setAccessToken($_SESSION['access_token']);

        $event = new Google_Service_Calendar_Event([
            'summary' => $title,
            'start' => ['dateTime' => $startTime, 'timeZone' => $timezone],
            'end' => ['dateTime' => $endTime, 'timeZone' => $timezone],
            'conferenceData' => [
                'createRequest' => ['requestId' => uniqid()]
            ]
        ]);

        $calendarId = 'primary';
        $event = $this->service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

        return $event->getHangoutLink(); // Returns Google Meet Link
    }
}