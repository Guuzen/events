<?php

declare(strict_types=1);

namespace App\User;

use App\Common\JsonDocumentType;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonDocumentTypes
{
    /** @var string[] */
    private $types;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function addType(string $type): void
    {
        $this->types[$type] = $type;
    }

    public function initialize(): void
    {
        $this->serializer->serialize(['foo'], 'json');
        foreach ($this->types as $type) {
            if (!Type::hasType($type)) {
                Type::addType($type, JsonDocumentType::class);

                /** @var JsonDocumentType $jsonDocumentType */
                $jsonDocumentType = Type::getType($type);
                $jsonDocumentType->setClassName($type);
                $jsonDocumentType->setName($type);
            }
        }
    }
}
