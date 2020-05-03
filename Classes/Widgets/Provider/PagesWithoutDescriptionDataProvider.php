<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets\Provider;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class PagesWithoutDescriptionDataProvider implements PageProviderInterface
{
    /**
     * @var array
     */
    private $excludedDoktypes;

    /**
     * @var int
     */
    private $limit;

    public function __construct(array $excludedDoktypes, int $limit)
    {
        $this->excludedDoktypes = $excludedDoktypes;
        $this->limit = $limit ?: 5;
    }

    public function getPages(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');

        $constraints = [
            $queryBuilder->expr()->notIn('doktype', $this->excludedDoktypes),
            $queryBuilder->expr()->eq('no_index', 0),
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq('description', $queryBuilder->createNamedParameter('')),
                $queryBuilder->expr()->isNull('description')
            ),
        ];

        $items = [];

        $results = $queryBuilder
            ->select('*')
            ->from('pages')
            ->where(...$constraints)
            ->orderBy('tstamp', 'DESC')
            ->setMaxResults($this->limit)
            ->execute()
            ->fetchAll();

        foreach($results as $row) {
            if (!$this->getBackendUser()->doesUserHaveAccess($row, Permission::PAGE_SHOW)) {
                continue;
            }

            $items[] = $row;
        }

        return $items;
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
