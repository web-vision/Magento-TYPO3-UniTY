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

$locallandDb = 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:';

$additioncalColumns = array(
    'unity_path'    => array(
        'exclude' => 1,
        'label'   => $locallandDb . 'tx_wvt3unity_domain_model_pagelanguageoverlay.path',
        'config'  => array(
            'type' => 'input',
            'size' => 70,
            'max'  => 70,
            'eval' => 'trim',
        ),
    ),
    'canonical_url' => array(
        'exclude' => 1,
        'label'   => $locallandDb . 'tx_wvt3unity_domain_model_pagelanguageoverlay.canonical_url',
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
    'full_path, path, canonical_url',
    1,
    'before:keywords'
);

unset($additioncalColumns);
unset($locallandDb);
