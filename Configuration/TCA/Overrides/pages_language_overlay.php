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
            ]
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            $table,
            'canonical_url',
            1,
            'before:keywords'
        );
    },
    'wv_t3unity',
    'pages_language_overlay'
);
