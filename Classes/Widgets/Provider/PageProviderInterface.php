<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\Widgets\Widgets\Provider;

interface PageProviderInterface
{
    /**
     * @return array
     */
    public function getPages(): array;
}
