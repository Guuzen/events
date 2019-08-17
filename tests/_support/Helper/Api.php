<?php

namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\REST;

class Api extends \Codeception\Module
{
    private const ID_PATH = '$.data.id';

    public function seeResponseContainsId(): void
    {
        /** @var REST $restModule */
        $restModule = $this->getModule('REST');
        $restModule->seeResponseMatchesJsonType(['string'], self::ID_PATH); // TODO uuid check ?
    }

    public function grabIdFromResponse(): string
    {
        /** @var REST $restModule */
        $restModule = $this->getModule('REST');

        return $restModule->grabDataFromResponseByJsonPath(self::ID_PATH)[0];
    }
}
