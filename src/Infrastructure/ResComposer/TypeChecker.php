<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

final class TypeChecker
{
    /**
     * @psalm-assert array-key|null $value
     *
     * @param mixed $value
     */
    public static function assertThatValueIsArrayKeyOrNull($value): void
    {
        if ($value === null) {
            return;
        }

        if (\is_string($value) === true) {
            return;
        }

        if (\is_int($value) === true) {
            return;
        }

        throw new \RuntimeException();
    }

    /**
     * @psalm-assert array $value
     *
     * @param mixed $value
     */
    public static function assertThatValueIsIterable($value): void
    {
        if (\is_array($value) === true) {
            return;
        }

        throw new \RuntimeException('Expected array, ' . \gettype($value) . ' given');
    }
}
