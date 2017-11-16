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

        $unityAuthenticationService = WebVision\WvT3unity\Service\UnityAuthenticationService::class;

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
                        'TYPO3\\CMS\\Backend\\Routing\\UriBuilder' => [
                            'className' => 'WebVision\\WvT3unity\\Backend\\Routing\\UriBuilder'
                        ],
                        'TYPO3\\CMS\\Recordlist\\RecordList' => [
                            'className' => 'WebVision\\WvT3unity\\Recordlist\\RecordList'
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

        // Add the service
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
            $extKey,
            'auth',
            $unityAuthenticationService,
            [
                'title' => 'Unity Authentication',
                'description' => 'Authenticates with Unity',
                'subtype' => 'getUserBE,authUserBE',
                'available' => true,
                'priority' => 80,
                'quality' => 80,
                'os' => '',
                'exec' => '',
                'className' => $unityAuthenticationService,
            ]
        );

        // Register icon for clearing cache
        \WebVision\WvT3unity\Utility\IconRegistry::registerIcons();
    },
    $_EXTKEY
);
