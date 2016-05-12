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
        $exclude = 'exclude';
        $label = 'label';
        $config = 'config';
        $input = 'input';
        $pages = 'pages';

        $additionalColumns = array(
            'canonical_url'          => array(
                $exclude => 1,
                $label   => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:pages.canonical_url',
                $config => array(
                    'type' => $input,
                    'size' => 70,
                    'max'  => 70,
                    'eval' => 'trim,nospace,lower',
                ),
            ),
            'tx_realurl_pathsegment' => array(
                $label => 'LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.tx_realurl_pathsegment',
                'displayCond' => 'FIELD:tx_realurl_exclude:!=:1',
                $exclude => 1,
                $config => array (
                    'type' => 'input',
                    'max' => 255,
                    'eval' => 'trim,nospace,lower,DmitryDulepov\\Realurl\\Evaluator\\SegmentFieldCleaner',
                    'readOnly' => 1,
                ),
            ),
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($pages, $additionalColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            $pages,
            'canonical_url',
            1,
            'before:keywords'
        );

        // remove tx_realurl_pathoverride from backend
        $GLOBALS['TCA'][$pages]['palettes']['137']['showitem'] = '';
    }
);
