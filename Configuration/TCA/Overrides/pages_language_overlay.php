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
    function ($extKey, $table) {
        // Localization paths
        $locallang = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            $table,
            [
                'canonical_url' => [
                    'exclude' => 1,
                    'label'   => $locallang . 'pages_language_overlay.canonical_url',
                    'config'  => [
                        'type' => 'input',
                        'size' => 70,
                        'max'  => 70,
                        'eval' => 'trim',
                    ],
                ],
                'tx_realurl_pathsegment' => [
                    'label' => $locallang . 'pages.tx_realurl_pathsegment',
                    'displayCond' => 'FIELD:tx_realurl_exclude:!=:1',
                    'exclude' => 1,
                    'config' => [
                        'type' => 'input',
                        'size' => 70,
                        'max' => 255,
                        'eval' => 'trim,nospace,lower,WebVision\\WvT3Unity\\Evaluator\\SegmentFieldCleaner',
                    ],
                ],
                'tx_realurl_pathoverride' => [
                    'label' => 'LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.tx_realurl_path_override',
                    'exclude' => 1,
                    'config' => [
                        'type' => 'check',
                        'items' => [
                            ['LLL:EXT:lang/locallang_core.xlf:labels.enabled', '']
                        ],
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

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            $table,
            'tx_realurl_pathoverride',
            1,
            'after:tx_realurl_pathsegment'
        );
    },
    'wv_t3unity',
    'pages_language_overlay'
);
