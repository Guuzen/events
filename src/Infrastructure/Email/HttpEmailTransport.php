<?php

declare(strict_types=1);

namespace App\Infrastructure\Email;

use GuzzleHttp\ClientInterface;
use Swift_Events_EventListener;
use Swift_Mime_SimpleMessage;
use Swift_Transport;

/**
 * Transport for send emails in test environment
 */
final class HttpEmailTransport implements Swift_Transport
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function isStarted(): bool
    {
        return true;
    }

    public function start(): void
    {

    }

    public function stop(): void
    {

    }

    public function ping(): bool
    {
        return true;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null): int
    {
        $this->client->request('POST', '/send_ticket_email', [
            'json' => [
                'subject' => $message->getSubject(),
                'from'    => $message->getFrom(),
                'to'      => $message->getTo(),
            ],
        ]);

        return 1;
    }

    public function registerPlugin(Swift_Events_EventListener $plugin): void
    {

    }
}
