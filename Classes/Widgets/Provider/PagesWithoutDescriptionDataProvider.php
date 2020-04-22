<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets\Provider;

use TYPO3\CMS\Backend\Routing\UriBuilder;

class PagesWithoutDescriptionDataProvider implements PageProviderInterface
{
    /**
     * @var UriBuilder
     */
    private $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
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
