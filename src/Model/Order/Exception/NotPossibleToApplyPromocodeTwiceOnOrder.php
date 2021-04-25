<?php

declare(strict_types=1);

namespace App\Model\Order\Exception;

use App\Infrastructure\AppException\AppException;

final class NotPossibleToApplyPromocodeTwiceOnOrder extends AppException
{
}
