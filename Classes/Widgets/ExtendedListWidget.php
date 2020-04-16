<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Dashboard\Utility\ButtonUtility;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\ListDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class ExtendedListWidget implements WidgetInterface, AdditionalCssInterface
{
    /**
     * @var WidgetConfigurationInterface
     */
    private $configuration;
    /**
     * @var ListDataProviderInterface
     */
    private $dataProvider;
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
        ListDataProviderInterface $dataProvider,
        StandaloneView $view,
        $buttonProvider = null,
        array $options = []
    ) {
        $this->configuration = $configuration;
        $this->dataProvider = $dataProvider;
        $this->view = $view;
        $this->buttonProvider = $buttonProvider;
        $this->options = array_merge(
            [
                'template' => 'Widget/ExtendedListWidget',
            ],
            $options
        );
    }

    public function getCssFiles(): array
    {
        return [
            'EXT:widgets/Resources/Public/Css/extendedListWidget.css',
        ];
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate($this->options['template']);

        $this->view->assignMultiple([
            'items' => $this->dataProvider->getItems(),
            'options' => $this->options,
            'currentUser' => $this->getBackendUser(),
            'button' => ButtonUtility::generateButtonConfig($this->buttonProvider),
            'configuration' => $this->configuration,
            'dateFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
            'timeFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
        ]);
        return $this->view->render();
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
