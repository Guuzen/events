<?php

namespace App\EventDomain\Queries\FindEventIdByDomain;

use App\Infrastructure\AppException\AppException;

// TODO check all runtime exceptions maybe they must extends from AppException
final class EventIdByDomainNotFound extends AppException
{
}
