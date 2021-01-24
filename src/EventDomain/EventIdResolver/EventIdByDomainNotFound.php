<?php

namespace App\EventDomain\EventIdResolver;

use App\Infrastructure\AppException\AppException;

final class EventIdByDomainNotFound extends AppException
{
}
