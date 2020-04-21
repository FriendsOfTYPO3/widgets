<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets\Provider;

class PagesWithoutDescriptionDataProvider implements PageProviderInterface
{
    public function getPages(): array
    {
        return [
            [
                'uid' => 1,
                'title' => 'title'
            ],
            [
                'uid' => 2,
                'title' => 'title 2'
            ],
        ];
    }
}
