<?php

namespace PhpMonsters\LaravelPostalDriver;

use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;
use GuzzleHttp\Client;

class PostalTransport extends Transport
{
    protected string $key;
    protected string $endpoint;
    protected Client $client;

    public function __construct(string $key, string $endpoint)
    {
        $this->key = $key;
        $this->endpoint = rtrim($endpoint, '/');
        $this->client = new Client();
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null): int
    {
        $to = array_keys($message->getTo())[0];
        $from = array_keys($message->getFrom())[0];
        $subject = $message->getSubject();
        $html = $message->getBody();
        $plain = strip_tags($html);

        $response = $this->client->post($this->endpoint . '/api/v1/send/message', [
            'headers' => [
                'X-Server-API-Key' => $this->key,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'to' => $to,
                'from' => $from,
                'subject' => $subject,
                'plain_body' => $plain,
                'html_body' => $html
            ]
        ]);

        return $this->numberOfRecipients($message);
    }
}
