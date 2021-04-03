<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

/**
 * @template WriteObject of object
 */
final class Promise
{
    /**
     * @var callable(object): ?string
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
     * @param callable(object): ?string $idExtractor
     * @param callable(WriteObject, mixed): void $writer
     * @param WriteObject $object
     */
    public function __construct($idExtractor, $writer, $object)
    {
        $this->idExtractor = $idExtractor;
        $this->writer      = $writer;
        $this->object      = $object;
    }

    /**
     * @psalm-mutation-free
     *
     * @param string $idProperty
     * @param string $writeProperty
     */
    public static function withProperties(
        string $idProperty,
        string $writeProperty,
        object $object
    ): self
    {
        $idExtractor = static function (object $object) use ($idProperty): ?string {
            if (\property_exists($object, $idProperty) === false) {
                throw new \RuntimeException(
                    \sprintf('Property %s do not exists in class %s', $idProperty, \get_class($object))
                );
            }

            $id = $object->$idProperty;
            if ($id !== null && \is_string($id) !== true) {
                throw new \RuntimeException(
                    \sprintf(
                        'Value of property %s for class %s must be null or string',
                        $idProperty,
                        \get_class($object)
                    )
                );
            }

            return $id;
        };

        $writer =
            /** @param mixed $value */
            static function (object $object, $value) use ($writeProperty): void {
                if (\property_exists($object, $writeProperty) === false) {
                    throw new \RuntimeException(
                        \sprintf('Property %s do not exists in class %s', $writeProperty, \get_class($object))
                    );
                }

                $object->$writeProperty = $value;
            };

        return new self($idExtractor, $writer, $object);
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
