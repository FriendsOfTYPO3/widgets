<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(static function () {
    if (TYPO3_MODE === 'BE') {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            '
module.tx_dashboard {
    view {
        templateRootPaths.1586987083 = EXT:widgets/Resources/Private/Templates/
        partialRootPaths.1586987083 = EXT:widgets/Resources/Private/Partials/
        layoutRootPaths.1586987083 = EXT:widgets/Resources/Private/Layouts/
    }
}'
        );
    }
});
