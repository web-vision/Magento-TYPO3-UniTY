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

        // EID Call for clearing the TYPO3 Cache
        $GLOBALS[$typo3ConfigurationVariables]['FE']['eID_include'][$extKey . '_clearCache'] = 
            WebVision\WvT3unity\Controller\Eid\ClearCacheFromRemote::class .'::clearCacheAction';

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
                        'clearCachePostProc' => [
                            $extKey => WebVision\WvT3unity\Hooks\DataHandler::class . '->clearAdditionalCache',
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
                    'additionalBackendItems' => [
                        'cacheActions' => [
                            $extKey => WebVision\WvT3unity\Hooks\Backend\Toolbar\ClearCacheActionsHook::class
                        ]
                    ]
                ],
            ]
        );
    },
    $_EXTKEY
);


