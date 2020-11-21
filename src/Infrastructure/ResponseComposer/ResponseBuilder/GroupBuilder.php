<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer\ResponseBuilder;

use App\Infrastructure\ResponseComposer\ResourceLink\ResourceLink;

final class GroupBuilder implements ResponseBuilder
{
    private $builders;

    /**
     * @param SingleBuilder[] $builders
     */
    public function __construct(array $builders)
    {
        $this->builders = $builders;
    }

    /**
     * @return object[]
     */
    public function build(): array
    {
        $responses = [];
        foreach ($this->builders as $builder) {
            $responses[] = $builder->build();
        }

        return $responses;
    }

    /**
     * @return ResponseBuilder[]
     */
    public function group(ResourceLink $link): array
    {
        return $link->group($this->builders);
    }
}
