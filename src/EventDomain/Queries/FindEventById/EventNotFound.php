<?php

namespace App\EventDomain\Queries\FindEventById;

use App\Infrastructure\AppException\AppException;

final class EventNotFound extends AppException
{
}
