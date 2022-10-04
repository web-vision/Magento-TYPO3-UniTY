<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

(function ($extKey, $table) {
    ExtensionManagementUtility::addTCAcolumns(
        $table,
        [
            'canonical_url' => [
                'exclude' => 1,
                'label'   => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:' . 'pages.canonical_url',
                'config' => [
                    'type' => 'input',
                    'size' => 70,
                    'max'  => 70,
                    'eval' => 'trim,nospace,lower',
                ],
            ],
            'tx_realurl_pathsegment' => [
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:' . 'pages.tx_realurl_pathsegment',
                'displayCond' => 'FIELD:tx_realurl_exclude:!=:1',
                'exclude' => 1,
                'config' => [
                    'type' => 'input',
                    'max' => 255,
                    'eval' => 'trim,nospace,lower,DmitryDulepov\\Realurl\\Evaluator\\SegmentFieldCleaner',
                    'readOnly' => 0,
                ],
            ],
        ]
    );

    ExtensionManagementUtility::addToAllTCAtypes(
        $table,
        'canonical_url',
        1,
        'before:keywords'
    );

    ExtensionManagementUtility::registerPageTSConfigFile(
        $extKey,
        'Configuration/PageTS/Include.ts',
        'web-vision UniTY - Config'
    );
}
)('wv_t3unity', 'pages');
