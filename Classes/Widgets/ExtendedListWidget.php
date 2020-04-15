<?php
declare(strict_types=1);
namespace FriendsOfTYPO3\Widgets\Widgets;

use TYPO3\CMS\Dashboard\Utility\ButtonUtility;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\ListDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class ExtendedListWidget implements WidgetInterface
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

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate($this->options['template']);

        $this->view->assignMultiple([
            'items' => $this->dataProvider->getItems(),
            'options' => $this->options,
            'button' => ButtonUtility::generateButtonConfig($this->buttonProvider),
            'configuration' => $this->configuration,
            'dateFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
            'timeFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
        ]);
        return $this->view->render();
    }
}
