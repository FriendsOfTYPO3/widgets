<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets;

use _HumbugBox3ab8cff0fda0\ParagonIE\Sodium\Core\Curve25519\Ge\P1p1;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Utility\ButtonUtility;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\ListDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\WidgetInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Reports\Report\Status\Status;
use TYPO3\CMS\Reports\ReportInterface;
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
                'showWarnings' => false
            ],
            $options
        );
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate('Widget/ReportsWidget');

        $this->view->assignMultiple([
            'options' => $this->options,
            'reports' => $this->getWarningsAndErrors(),
            'button' => ButtonUtility::generateButtonConfig($this->buttonProvider),
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
        $statuses = $this->sortStatusProviders($statusReport->getSystemStatus());
        $allowedSeverities = [];

        if ($this->options['showErrors']) {
            $allowedSeverities[] = ReportStatus::ERROR;
        }
        if ($this->options['showWarnings']) {
            $allowedSeverities[] = ReportStatus::WARNING;
        }

        $errors = [];
        foreach ($statuses as $provider => $providerStatus) {
            $errors[$provider] = $this->sortAndFilterStatuses($providerStatus, $allowedSeverities);
        }
        return $errors;
    }

    protected function sortStatusProviders(array $statusCollection): array
    {
        $primaryStatuses = [
            $this->getLanguageService()->getLL('status_typo3') => $statusCollection['typo3'],
            $this->getLanguageService()->getLL('status_system') => $statusCollection['system'],
            $this->getLanguageService()->getLL('status_security') => $statusCollection['security'],
            $this->getLanguageService()->getLL('status_configuration') => $statusCollection['configuration']
        ];
        unset($statusCollection['typo3'], $statusCollection['system'], $statusCollection['security'], $statusCollection['configuration']);
        $secondaryStatuses = [];
        foreach ($statusCollection as $statusProviderId => $collection) {
            if (strpos($statusProviderId, 'LLL:') === 0) {
                // Label provided by extension
                $label = $this->getLanguageService()->sL($statusProviderId);
            } else {
                // Generic label
                $label = $this->getLanguageService()->getLL('status_' . $statusProviderId);
            }
            $providerLabel = empty($label) ? $statusProviderId : $label;
            $secondaryStatuses[$providerLabel] = $collection;
        }
        // Sort the secondary status collections alphabetically
        ksort($secondaryStatuses);
        return array_merge($primaryStatuses, $secondaryStatuses);
    }

    protected function sortAndFilterStatuses(array $statusCollection, array $allowedSeverities)
    {
        $statuses = [];
        $sortTitle = [];
        $header = null;
        /** @var ReportStatus $status */
        foreach ($statusCollection as $status) {
            if (in_array($status->getSeverity(), $allowedSeverities)) {
                if ($status->getTitle() === 'TYPO3') {
                    $header = $status;
                    continue;
                }
                $statuses[] = $status;
                $sortTitle[] = $status->getSeverity();
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
