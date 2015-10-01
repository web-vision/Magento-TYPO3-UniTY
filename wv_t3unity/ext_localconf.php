<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['FE']['pageOverlayFields'] .= ',unity_path,canonical_url';

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['tx_wvt3unity'] =
    'WebVision\WvT3unity\Hooks\Tcemain';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['tx_wvt3unity'] =
    'WebVision\WvT3unity\Hooks\Tcemain';
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all']['tx_wvt3unity'] =
    'WebVision\WvT3unity\Hooks\ContentPostProc->contentPostProc';
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output']['tx_wvt3unity'] =
    'WebVision\WvT3unity\Hooks\ContentPostProc->contentPostProc';

// do not move to ext_tables.php otherwise config will not be found always
\WebVision\WvCore\Utility\ContentElementUtility::addContentElementTyposcript($_EXTKEY, 'MagentoProductList', 0);
