<?php
declare(strict_types=1);

namespace App\Infrastructure\Notifier;

final class BatchNotifier implements Notifier
{
    /** @var Notifier[] */
    private $notifiers;

    public function __construct(array $notifiers)
    {
        $this->notifiers = $notifiers;
    }

    public function notify(array $event)
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->notify($event);
        }
    }
}
