<?php

use WebVision\WvT3unity\Hooks\PageHook;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Backend\Controller\SimpleDataHandlerController;

(function () {
    ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:wv_t3unity/Configuration/PageTS/Include.tsconfig">'
    );
    // This can probably be remove or needs reimplementation
    // ArrayUtility::mergeRecursiveWithOverrule(
    //     $GLOBALS['TYPO3_CONF_VARS'],
    //     [
    //         'SC_OPTIONS' => [
    //             't3lib/class.t3lib_tcemain.php' => [
    //                 'processDatamapClass' => [
    //                     'wv_t3unity' => Tcemain::class,
    //                 ],
    //                 'processCmdmapClass' => [
    //                     'wv_t3unity' => Tcemain::class,
    //                 ],
    //             ],
    //             'tslib/class.tslib_fe.php' => [
    //                 'contentPostProc-all' => [
    //                     'wv_t3unity' => ContentPostProc::class . '->hookEntry',
    //                 ],
    //                 'contentPostProc-output' => [
    //                     'wv_t3unity' => ContentPostProc::class . '->hookEntry',
    //                 ],
    //             ],
    //         ],
    //     ]
    // );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions']['mag_cache'] = 'WebVision\\WvT3unity\\Hooks\\ClearMagCache';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook']['mag_cache'] = PageHook::class . '->render';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'] =
        [
            SimpleDataHandlerController::class => [
                'className' => WebVision\WvT3unity\Xclass\SimpleDataHandlerController::class,
            ]
        ];

    // Exclude cHash validation for certain parameters
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'] = [
        'colPos','uid','entry-level'
    ];
})();
