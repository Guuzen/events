<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\ResourceComposer;

use App\Infrastructure\Persistence\ResultSetMapping;
use Doctrine\DBAL\Connection;
use Guuzen\ResourceComposer\ResourceLoader;

final class PostgresLoader implements ResourceLoader
{
    private $connection;

    private $table;

    private $searchField;

    /**
     * @var string[]
     */
    private $fields;

    /**
     * @param string[] $fields
     */
    public function __construct(Connection $connection, string $table, string $searchField, array $fields = [])
    {
        $this->connection  = $connection;
        $this->table       = $table;
        $this->searchField = $searchField;
        $this->fields      = $fields;
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function load(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        /** @var \Doctrine\DBAL\Driver\PDO\Connection $pdo */
        $pdo = $this->connection->getWrappedConnection();

        if ($this->fields === []) {
            $fieldsStatement = '*';
        } else {
            $fieldsStatement = \implode(', ', $this->fields);
        }

        $stmt = $pdo->prepare(
            '
            select
                ' . $fieldsStatement . '
            from
                ' . $this->table . '
            where
                ' . $this->searchField . ' in (' . \implode(', ', \array_fill(0, \count($ids), '?')) . ')
            '
        );
        $stmt->execute($ids);
        /** @var array<int, array> $set */
        $set = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $mapping = ResultSetMapping::forStatement($stmt);
        $row     = $mapping->mapKnownColumnsArray($this->connection->getDatabasePlatform(), $set);

        return $row;
    }

    public function loadBy(): string
    {
        return $this->searchField;
    }
}
