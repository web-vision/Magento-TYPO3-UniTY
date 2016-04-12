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
        $locallangDb = 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.';

        $additionalColumns = array(
            'hidden_xs' => array(
                'exclude' => 1,
                'label'   => $locallangDb . 'hidden_xs',
                'config'  => array(
                    'type' => 'check',
                ),
            ),
            'hidden_sm' => array(
                'exclude' => 1,
                'label'   => $locallangDb . 'hidden_sm',
                'config'  => array(
                    'type' => 'check',
                ),
            ),
            'hidden_md' => array(
                'exclude' => 1,
                'label'   => $locallangDb . 'hidden_md',
                'config'  => array(
                    'type' => 'check',
                ),
            ),
            'hidden_lg' => array(
                'exclude' => 1,
                'label'   => $locallangDb . 'hidden_lg',
                'config'  => array(
                    'type' => 'check',
                ),
            ),
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
            'tt_content',
            'responsive',
            'hidden_xs;'
            . $locallangDb
            . 'hidden_xs, hidden_sm;'
            . $locallangDb
            . 'hidden_sm, hidden_md;'
            . $locallangDb
            . 'hidden_md, hidden_lg;'
            . $locallangDb
            . 'hidden_lg'
        );

        // WVTODO add palett responsive to all types after frames
        /*\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--palette--;Responsive;responsive',
            '',
            'after:frames'
        );*/
    }
);
