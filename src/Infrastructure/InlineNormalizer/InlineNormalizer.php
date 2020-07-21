<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class InlineNormalizer implements DenormalizerInterface
{
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedReturnStatement
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {

        if (\count($data) > 1) {
            throw new UnexpectedValueException(
                \sprintf('It is not possible to inline more than one value. Type: %s.', $type)
            );
        }

        // TODO array_key_first from php 7.3 ?
        /** @psalm-suppress MixedArrayAccess */
        $firstKey = \key($data);

        /** @psalm-suppress MixedArrayAccess */
        return $data[$firstKey];
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        $reflectionClass = new \ReflectionClass($type);
        /** @var InlineDenormalizable|null $annotation */
        $annotation = $this->reader->getClassAnnotation($reflectionClass, InlineDenormalizable::class);

        return $annotation !== null;
    }
}
