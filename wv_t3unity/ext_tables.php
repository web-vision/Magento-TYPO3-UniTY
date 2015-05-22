<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Unity Basic');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/XmlSitemap', 'Unity XML Sitemap');

// remove tx_realurl_pathoverride from backend
$GLOBALS['TCA']['pages']['palettes']['137']['showitem'] = '';

/**
 * BackendLayoutDataProvider
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['BackendLayoutDataProvider'][$_EXTKEY] = 'WebVision\WvT3unity\Hooks\Options\BackendLayoutDataProvider';
