<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
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
                            $extKey => 'WebVision\WvT3unity\Hooks\Tcemain',
                        ],
                        'processCmdmapClass'  => [
                            $extKey => 'WebVision\WvT3unity\Hooks\Tcemain',
                        ],
                    ],
                    'tslib/class.tslib_fe.php'      => [
                        'contentPostProc-all'    => [
                            $extKey => 'WebVision\WvT3unity\Hooks\ContentPostProc->hookEntry',
                        ],
                        'contentPostProc-output' => [
                            $extKey => 'WebVision\WvT3unity\Hooks\ContentPostProc->hookEntry',
                        ],
                    ],
                ],
            ]
        );
    },
    $_EXTKEY
);
