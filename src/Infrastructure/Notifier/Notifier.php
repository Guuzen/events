<?php


namespace App\Infrastructure\Notifier;

interface Notifier
{
    public function notify(array $event);
}
