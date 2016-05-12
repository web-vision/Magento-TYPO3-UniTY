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

$GLOBALS['TYPO3_CONF_VARS']['FE']['pageOverlayFields'] .= ',canonical_url';

\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'],
    array(
        't3lib/class.t3lib_tcemain.php' => array(
            'processDatamapClass' => array(
                $_EXTKEY => 'WebVision\WvT3unity\Hooks\Tcemain',
            ),
            'processCmdmapClass'  => array(
                $_EXTKEY => 'WebVision\WvT3unity\Hooks\Tcemain',
            ),
        ),
        'tslib/class.tslib_fe.php'      => array(
            'contentPostProc-all'    => array(
                $_EXTKEY => 'WebVision\WvT3unity\Hooks\ContentPostProc->hookEntry',
            ),
            'contentPostProc-output' => array(
                $_EXTKEY => 'WebVision\WvT3unity\Hooks\ContentPostProc->hookEntry',
            ),
        ),
    )
);

// add backend layouts
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/PageTS/Mod/web_layout.txt">'
);
