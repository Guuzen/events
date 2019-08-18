<?php

namespace App\Tests\Module;

use Codeception\Util\JsonType;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;

final class Symfony extends \Codeception\Module\Symfony
{
    public function _initialize()
    {
        parent::_initialize();

        JsonType::addCustomFilter('date', function ($value) {
            return preg_match(
                '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/',
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
        /** @var Connection $connection */
        $connection = $this->_getEntityManager()->getConnection();
        $connection->exec('
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
