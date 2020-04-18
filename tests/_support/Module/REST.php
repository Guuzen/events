<?php

declare(strict_types=1);

namespace App\Tests\Module;

use Codeception\Util\JsonType;
use Ramsey\Uuid\Uuid;

final class REST extends \Codeception\Module\REST
{
    public function _initialize(): void
    {
        parent::_initialize();

        JsonType::addCustomFilter('date', function ($value) {
            return preg_match(
                '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})$/',
                $value
            );
        });

        JsonType::addCustomFilter('uuid', function ($value) {
            return Uuid::isValid($value);
        });
    }

    public function _before(\Codeception\TestInterface $test)
    {
        parent::_before($test);
        $pdo = new \PDO('pgsql:host=guuzen-events-pgsql;port=5432;dbname=guuzen-events-test;user=user;password=password');

        $pdo->exec('
            DO $$
            BEGIN
                execute
                (
                    select \'truncate table \' || string_agg(quote_ident(table_name), \', \')
                    from information_schema.tables
                    where table_schema = \'public\'
                );
            END
            $$;
        ');
    }
}
