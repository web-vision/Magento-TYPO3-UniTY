<?php

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

call_user_func(
    function ($extKey) {
        $typo3ConfigurationVariables = 'TYPO3_CONF_VARS';

        $GLOBALS[$typo3ConfigurationVariables]['FE']['pageOverlayFields'] .= ',canonical_url';

        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS[$typo3ConfigurationVariables],
            [
                'SC_OPTIONS' => [
                    't3lib/class.t3lib_tcemain.php' => [
                        'processDatamapClass' => [
                            $extKey => WebVision\WvT3unity\Hooks\Tcemain::class,
                        ],
                        'processCmdmapClass' => [
                            $extKey => WebVision\WvT3unity\Hooks\Tcemain::class,
                        ],
                    ],
                    'tslib/class.tslib_fe.php' => [
                        'contentPostProc-all' => [
                            $extKey => WebVision\WvT3unity\Hooks\ContentPostProc::class . '->hookEntry',
                        ],
                        'contentPostProc-output' => [
                            $extKey => WebVision\WvT3unity\Hooks\ContentPostProc::class . '->hookEntry',
                        ],
                    ],
                ],
            ]
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions']['mag_cache'] = 'WebVision\\WvT3unity\\Hooks\\ClearMagCache';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'] = 
        [
            TYPO3\CMS\Backend\Controller\SimpleDataHandlerController::class => [
                'className' => WebVision\WvT3unity\Xclass\SimpleDataHandlerController::class,
            ],
            TYPO3\CMS\Backend\ContextMenu\ItemProviders\PageProvider::class => [
                'className' => WebVision\WvT3unity\Xclass\PageProvider::class,
            ],
            TYPO3\CMS\Backend\Controller\PageLayoutController::class => [
                'className' => WebVision\WvT3unity\Xclass\PageLayoutController::class,
            ],
        ];
        
    },
    $_EXTKEY
);
