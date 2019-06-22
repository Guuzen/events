<?php
declare(strict_types=1);

namespace App\User\Model;

use Ramsey\Uuid\Uuid;

final class UserId
{
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function equals(UserId $userId): bool
    {
        return $this->id->equals($userId->id);
    }
}
