<?php
declare(strict_types=1);


namespace FriendsOfTYPO3\Widgets\Widgets\Provider;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;

/**
 * Provide link for sys log button.
 * Check whether belog is enabled and add link to module.
 * No link is returned if not enabled.
 */
class StatusReportButtonProvider implements ButtonProviderInterface
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $target;

    public function __construct(string $title, string $target = '')
    {
        $this->title = $title;
        $this->target = $target;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        return (string)$uriBuilder->buildUriFromRoute(
            'system_reports',
            ['action' => 'detail', 'extension' => 'tx_reports', 'report' => 'status']
        );
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}
