<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class ResultSetMapping
{
    /**
     * @var string[]
     */
    private static $postgresTypeMapping = [
        'timestamptz' => 'datetimetz_immutable',
        'timestamp'   => 'datetime_immutable',
        'json'        => 'json',
        'jsonb'       => 'json',
    ];

    private $mappings;

    /**
     * @param Type[] $mappings
     */
    private function __construct(array $mappings)
    {
        $this->mappings = $mappings;
    }

    public static function forStatement(\PDOStatement $statement): self
    {
        $resultSetMapping = [];
        for ($i = 0; $i < $statement->columnCount(); ++$i) {
            /**
             * @var array{
             *      native_type: string,
             *      name: string
             * } $meta
             */
            $meta = $statement->getColumnMeta($i);
            if (isset($meta['native_type'], self::$postgresTypeMapping[$meta['native_type']]) === true) {
                $resultSetMapping[$meta['name']] = Type::getType(self::$postgresTypeMapping[$meta['native_type']]);
            }
        }

        return new self($resultSetMapping);
    }

    public function mapColumn(AbstractPlatform $platform, string $columnName, mixed $value): mixed
    {
        if (!isset($this->mappings[$columnName])) {
            return $value;
        }

        return $this->mappings[$columnName]->convertToPHPValue($value, $platform);
    }

    public function mapKnownColumns(AbstractPlatform $platform, array $row): array
    {
        foreach ($this->mappings as $field => $type) {
            if (\array_key_exists($field, $row) === true) {
                /** @psalm-suppress MixedAssignment */
                $row[$field] = $type->convertToPHPValue($row[$field], $platform);
            }
        }

        return $row;
    }

    /**
     * @param array<int, array> $rows
     */
    public function mapKnownColumnsArray(AbstractPlatform $platform, array $rows): array
    {
        $mappedRows = [];
        foreach ($rows as $row) {
            $mappedRows[] = $this->mapKnownColumns($platform, $row);
        }

        return $mappedRows;
    }
}