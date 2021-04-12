<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

/**
 * @template WriteObject of object
 */
final class Promise
{
    /**
     * @var callable(mixed): ?string
     */
    private $idExtractor;

    /**
     * @var callable(WriteObject, mixed): void
     */
    private $writer;

    /**
     * @var WriteObject
     */
    private $object;

    /**
     * @param callable(mixed): ?string $idExtractor
     * @param callable(WriteObject, mixed): void $writer
     * @param WriteObject $object
     */
    public function __construct($idExtractor, $writer, $object)
    {
        $this->idExtractor = $idExtractor;
        $this->writer      = $writer;
        $this->object      = $object;
    }

    public function id(): ?string
    {
        return ($this->idExtractor)($this->object);
    }

    /**
     * @param mixed $value
     */
    public function resolve($value): void
    {
        ($this->writer)($this->object, $value);
    }
}
