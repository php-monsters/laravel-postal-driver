<?php

namespace PhpMonsters\LaravelPostalDriver;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mime\Email;
use GuzzleHttp\Client;

class PostalTransport implements TransportInterface
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(Client $client, string $apiKey, string $baseUrl)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function send(RawMessage $message, Envelope $envelope = null): SentMessage
    {
        if (!$message instanceof Email) {
            throw new \InvalidArgumentException('PostalTransport only supports Email messages.');
        }

        $payload = [
            'headers' => [
                'X-Server-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'from'      => $message->getFrom()[0]->getAddress(),
                'to'        => array_map(fn($to) => $to->getAddress(), $message->getTo()),
                'subject'   => $message->getSubject(),
                'plain_body'=> $message->getTextBody(),
                'html_body' => $message->getHtmlBody(),
            ],
        ];

        $this->client->post("{$this->baseUrl}/send/message", $payload);

        return new SentMessage($message, $envelope);
    }

    public function __toString(): string
    {
        return 'postal';
    }
}