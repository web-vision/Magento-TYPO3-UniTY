<?php

call_user_func(function ($extKey) {
    $tempColumns = [
        'tx_t3unity_standalone' => [
            'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xml:be_users.tx_t3unity_standalone',
            'exclude' => true,
            'config' => [
                'type' => 'check',
                'default' => '1',
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_users', $tempColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'be_users',
        'tx_t3unity_standalone',
        '',
        'after:password'
    );
}, 'wv_t3unity');
