<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayKeysNameConverter;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

final class ArrayKeysNameConverter
{
    /**
     * @var NameConverterInterface
     */
    private $nameConverter;

    public function __construct(NameConverterInterface $nameConverter)
    {
        $this->nameConverter = $nameConverter;
    }

    public function convert(array &$array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $camleizedKey = $this->nameConverter->denormalize($key);
            if (\is_array($value) === true) {
                $result[$camleizedKey] = $this->convert($value);
            } else {
                $result[$camleizedKey] = $value;
            }
        }

        return $result;
    }
}
