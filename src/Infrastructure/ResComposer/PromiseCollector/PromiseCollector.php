<?php
declare(strict_types=1);

namespace App\Infrastructure\ResComposer\PromiseCollector;

use App\Infrastructure\ResComposer\Promise;

interface PromiseCollector
{
    /**
     * @return Promise[]
     */
    public function collect(\ArrayObject $resource): array;
}
