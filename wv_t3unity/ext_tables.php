<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// load Typoscript config
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:' . $_EXTKEY . '/Configuration/PageTS">'
);

// add static Typoscript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Unity Basic');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript/XmlSitemap',
    'Unity XML Sitemap'
);

// remove tx_realurl_pathoverride from backend
$GLOBALS['TCA']['pages']['palettes']['137']['showitem'] = '';

// register BackendLayoutDataProvider to add own backend layouts
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['BackendLayoutDataProvider'][$_EXTKEY] =
    'WebVision\WvT3unity\Hooks\Options\BackendLayoutDataProvider';

// add own content elements
\WebVision\WvCore\Utility\ContentElementUtility::registerContentElement(
    $_EXTKEY,
    'MagentoProductList',
    '--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general, header, magento_skus,
    --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.appearance, --palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.frames;frames,
    --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, --palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.visibility;visibility, --palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.access;access,'
);
