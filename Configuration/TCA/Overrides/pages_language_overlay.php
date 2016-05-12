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
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function () {
        $locallandDb = 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:';

        $additionalColumns = array(
            'canonical_url' => array(
                'exclude' => 1,
                'label'   => $locallandDb . 'pages_language_overlay.canonical_url',
                'config'  => array(
                    'type' => 'input',
                    'size' => 70,
                    'max'  => 70,
                    'eval' => 'trim',
                ),
            ),
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages_language_overlay', $additionalColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'pages_language_overlay',
            'canonical_url',
            1,
            'before:keywords'
        );
    }
);
