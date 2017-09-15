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
        $languagePath = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xml:';

        $tempColumns = [
            'tx_t3unity_standalone' => [
                'label' => $languagePath . 'be_users.tx_t3unity_standalone',
                'exclude' => true,
                'config' => [
                    'type' => 'check',
                    'default' => '1',
                ],
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            'be_users', 
            $tempColumns
        );
        
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'be_users',
            'tx_t3unity_standalone',
            '',
            'after:password'
        );
    }, 
    'wv_t3unity'
);
