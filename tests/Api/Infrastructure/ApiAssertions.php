<?php

declare(strict_types=1);

namespace Tests\Api\Infrastructure;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherConstraint;
use PHPUnit\Framework\TestCase;

trait ApiAssertions
{
    protected function assertResultMatchesPattern(array $response, array $responsePattern, string $message = ''): void
    {
        TestCase::assertThat(
            \json_encode($response),
            new PHPMatcherConstraint(\json_encode($responsePattern)),
            $message
        );
    }
}
