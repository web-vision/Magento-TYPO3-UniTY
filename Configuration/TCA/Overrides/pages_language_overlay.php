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
