<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\Interfaces\ListDataProviderInterface;

class UsersOnlineDataProvider implements ListDataProviderInterface
{

    public function getItems(): array
    {
        return ['a', 'b'];
    }
}
