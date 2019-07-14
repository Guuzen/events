<?php
declare(strict_types=1);

namespace App\User\Doctrine;

use App\Common\JsonDocumentType;
use App\User\Model\Geo;

final class UserGeoType extends JsonDocumentType
{
    protected function className(): string
    {
        return Geo::class;
    }

    public function getName(): string
    {
        return 'app_user_geo';
    }
}
