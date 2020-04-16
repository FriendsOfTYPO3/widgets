<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets\Provider;

use TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Beuser\Domain\Repository\BackendUserSessionRepository;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\ListDataProviderInterface;

class UsersOnlineDataProvider implements ListDataProviderInterface
{

    /**
     * @var BackendUserRepository
     */
    private $backendUserRepository;
    /**
     * @var BackendUserSessionRepository
     */
    private $backendUserSessionRepository;

    public function __construct(BackendUserRepository $backendUserRepository, BackendUserSessionRepository $backendUserSessionRepository)
    {
        $this->backendUserRepository = $backendUserRepository;
        $this->backendUserSessionRepository = $backendUserSessionRepository;
    }

    public function getItems(): array
    {
        $onlineUsersAndSessions = [];
        $onlineUsers = $this->backendUserRepository->findOnline();
        foreach ($onlineUsers as $onlineUser) {
            $onlineUsersAndSessions[] = [
                'backendUser' => $onlineUser,
                'sessions' => $this->backendUserSessionRepository->findByBackendUser($onlineUser)
            ];
        }

        return $onlineUsersAndSessions;
    }
}
