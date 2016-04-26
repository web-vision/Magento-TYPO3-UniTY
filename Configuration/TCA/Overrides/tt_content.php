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

        $exclude = 'exclude';
        $label = 'label';
        $config = 'config';
        $type = 'type';
        $check = 'check';
        $hiddenXs = 'hidden_xs';
        $hiddenSm = 'hidden_sm';
        $hiddenMd = 'hidden_md';
        $hiddenLg = 'hidden_lg';

        $additionalColumns = array(
            $hiddenXs   => array(
                $exclude => 1,
                $label   => $locallangDb . $hiddenXs,
                $config  => array(
                    $type => $check,
                ),
            ),
            $hiddenSm   => array(
                $exclude => 1,
                $label   => $locallangDb . $hiddenSm,
                $config  => array(
                    $type => $check,
                ),
            ),
            $hiddenMd   => array(
                $exclude => 1,
                $label   => $locallangDb . $hiddenMd,
                $config  => array(
                    $type => $check,
                ),
            ),
            'hidden_lg' => array(
                $exclude => 1,
                $label   => $locallangDb . 'hidden_lg',
                $config  => array(
                    $type => $check,
                ),
            ),
        );

        $addFields = $hiddenXs . ';' . $locallangDb . $hiddenXs . ', ';
        $addFields .= $hiddenSm . ';' . $locallangDb . $hiddenSm . ', ';
        $addFields .= $hiddenMd . ';' . $locallangDb . $hiddenMd . ', ';
        $addFields .= $hiddenLg . ';' . $locallangDb . $hiddenLg;

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
            'tt_content',
            'responsive',
            $addFields
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
