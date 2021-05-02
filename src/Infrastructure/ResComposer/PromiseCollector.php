<?php
declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use App\Infrastructure\ResComposer\PromiseCollection\Promise;

interface PromiseCollector
{
    /**
     * @return Promise[]
     */
    public function collect(\ArrayObject $resource): array;
}
