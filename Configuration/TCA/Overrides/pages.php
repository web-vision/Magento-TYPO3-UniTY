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
    function ($extKey, $table) {
        // Localization paths
        $locallang = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:';

        // Configuration keys
        $config = 'config';
        $configExclude = 'exclude';
        $configLabel = 'label';

        // Configuration values
        $configTypeInput = 'input';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            $table,
            [
                'canonical_url' => [
                    $configExclude => 1,
                    $configLabel   => $locallang . 'pages.canonical_url',
                    $config => [
                        'type' => $configTypeInput,
                        'size' => 70,
                        'max'  => 70,
                        'eval' => 'trim,nospace,lower',
                    ],
                ],
                'tx_realurl_pathsegment' => [
                    $configLabel => $locallang . 'pages.tx_realurl_pathsegment',
                    'displayCond' => 'FIELD:tx_realurl_exclude:!=:1',
                    $configExclude => 1,
                    $config => [
                        'type' => $configTypeInput,
                        'max' => 255,
                        'eval' => 'trim,nospace,lower,DmitryDulepov\\Realurl\\Evaluator\\SegmentFieldCleaner',
                        'readOnly' => 1,
                    ],
                ],
            ]
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            $table,
            'canonical_url',
            1,
            'before:keywords'
        );

        // Remove tx_realurl_pathoverride from backend
        $GLOBALS['TCA'][$pages]['palettes']['137']['showitem'] = '';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
            $extKey,
            'Configuration/PageTS/Include.ts',
            'web-vision UniTY - Config'
        );
    },
    'wv_t3unity',
    'pages'
);
