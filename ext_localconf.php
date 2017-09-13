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
        $typo3ModulesRequestVariables = 'MODULE_REQUESTS';

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
                'SYS' => [
                    'Objects' => [
                        'TYPO3\\CMS\\Backend\\Controller\\PageLayoutController' => [
                            'className' => 'WebVision\\WvT3unity\\Backend\\Controller\\PageLayoutController'
                        ],
                        'TYPO3\\CMS\\Backend\\Template\\ModuleTemplate' => [
                            'className' => 'WebVision\\WvT3unity\\Backend\\Template\\ModuleTemplate'
                        ],
                        'TYPO3\\CMS\\Backend\\Tree\\View\\PageTreeView' => [
                            'className' => 'WebVision\\WvT3unity\\Backend\\Tree\\View\\StandalonePageTreeView'
                        ],
                    ],
                ],
            ]
        );

        if (! is_array($GLOBALS[$typo3ModulesRequestVariables])) {
            $GLOBALS[$typo3ModulesRequestVariables] = [];
        }

        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS[$typo3ModulesRequestVariables],
            [
                'allowedModules' => [
                    'page',
                    'list',
                ],
                'moduleMapping' => [
                    'web_layout' => [
                        'page',
                    ],
                    'web_list' => [
                        'list',
                    ],
                ],
            ]
        );
    },
    $_EXTKEY
);
