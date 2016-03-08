<?php
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Tim Werdin  <t.werdin@web-vision.de>, web-vision GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

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
//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;Responsive;responsive', '', 'after:frames');

unset($additionalColumns);
unset($locallangDb);
