<?php
declare(strict_types=1);

namespace App\Infrastructure\Notifier\Email;

use App\Infrastructure\Notifier\Notifier;
use Swift_Mailer;

final class Email implements Notifier
{
    private $mailer;

    private $emailFactory;

    public function __construct(Swift_Mailer $mailer, Factory $emailFactory)
    {
        $this->mailer = $mailer;
        $this->emailFactory = $emailFactory;
    }

    public function notify(array $event)
    {
        $message = $this->emailFactory->create($event);

        $this->mailer->send($message);
    }
}
