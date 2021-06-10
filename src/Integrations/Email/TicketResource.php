<?php

declare(strict_types=1);

namespace App\Integrations\Email;

use Guuzen\ResourceComposer\Config\DefaultMainResource;

final class TicketResource extends DefaultMainResource
{
    protected function config(): void
    {
        $this->hasOne(
            UserResource::class,
            'user_id',
            'user',
        );
    }
}
