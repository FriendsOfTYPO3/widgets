<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Reports\Status as ReportStatus;

class ReportsWidget implements WidgetInterface, AdditionalCssInterface
{
    /**
     * @var WidgetConfigurationInterface
     */
    private $configuration;
    /**
     * @var StandaloneView
     */
    private $view;
    /**
     * @var null
     */
    private $buttonProvider;
    /**
     * @var array
     */
    private $options;

    public function __construct(
        WidgetConfigurationInterface $configuration,
        StandaloneView $view,
        $buttonProvider = null,
        array $options = []
    ) {
        $this->configuration = $configuration;
        $this->view = $view;
        $this->buttonProvider = $buttonProvider;
        $this->options = array_merge(
            [
                'showErrors' => true,
                'showWarnings' => true
            ],
            $options
        );
    }

    protected function getStatusProvider($statusProvider)
    {
        if (strpos($statusProvider, 'LLL:') === 0) {
            // Label provided by extension
            $label = $this->getLanguageService()->sL($statusProvider);
        } else {
            // Generic label
            $label = $this->getLanguageService()->getLL('status_' . $statusProvider);
        }
        return empty($label) ? $statusProvider : $label;
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate('Widget/ReportsWidget');

        $this->view->assignMultiple([
            'options' => $this->options,
            'reports' => $this->getWarningsAndErrors(),
            'button' => $this->buttonProvider,
            'configuration' => $this->configuration,
            'dateFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
            'timeFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
        ]);
        return $this->view->render();
    }

    public function getCssFiles(): array
    {
        return [
            'EXT:widgets/Resources/Public/Css/reportsWidget.css',
        ];
    }

    protected function getWarningsAndErrors(): array
    {
        $statusReport = GeneralUtility::makeInstance(\TYPO3\CMS\Reports\Report\Status\Status::class);
        $allowedSeverities = [];

        if ($this->options['showErrors']) {
            $allowedSeverities[] = ReportStatus::ERROR;
        }
        if ($this->options['showWarnings']) {
            $allowedSeverities[] = ReportStatus::WARNING;
        }

        return $this->sortAndFilterStatuses($statusReport->getSystemStatus(), $allowedSeverities);
    }

    protected function sortAndFilterStatuses(array $systemStatus, array $allowedSeverities)
    {
        $statuses = [];
        $sortTitle = [];
        $header = null;
        /** @var ReportStatus $status */
        foreach ($systemStatus as $provider => $statusCollection) {
            foreach ($statusCollection as $status) {
                if (in_array($status->getSeverity(), $allowedSeverities)) {
                    $statuses[] = [$this->getStatusProvider($provider), $status];
                    $sortTitle[] = $status->getSeverity();
                }
            }
        }
        array_multisort($sortTitle, SORT_DESC, $statuses);
        return $statuses;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
