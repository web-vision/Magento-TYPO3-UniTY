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

$locallangDb = 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:';

$additionalColumns = array(
    'unity_path'             => array(
        'exclude' => 1,
        'label'   => $locallangDb . 'tx_wvt3unity_domain_model_pages.path',
        'config'  => array(
            'type' => 'input',
            'size' => 70,
            'max'  => 70,
            'eval' => 'trim',
        ),
    ),
    'canonical_url'          => array(
        'exclude' => 1,
        'label'   => $locallangDb . 'tx_wvt3unity_domain_model_pages.canonical_url',
        'config'  => array(
            'type' => 'input',
            'size' => 70,
            'max'  => 70,
            'eval' => 'trim',
        ),
    ),
    'tx_realurl_pathsegment' => array(
        'label'       => $locallangDb . 'pages.tx_realurl_pathsegment',
        'displayCond' => 'FIELD:tx_realurl_exclude:!=:1',
        'exclude'     => 1,
        'config'      => array(
            'type'     => 'input',
            'max'      => 255,
            'eval'     => 'trim,nospace,lower',
            'readOnly' => 1,
        ),
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $additionalColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'full_path, path, canonical_url',
    1,
    'before:keywords'
);

// remove tx_realurl_pathoverride from backend
$GLOBALS['TCA']['pages']['palettes']['137']['showitem'] = '';

// register BackendLayoutDataProvider to add own backend layouts
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['BackendLayoutDataProvider'][$_EXTKEY] = 'WebVision\WvT3unity\Hooks\Options\BackendLayoutDataProvider';

unset($additionalColumns);
unset($locallangDb);
