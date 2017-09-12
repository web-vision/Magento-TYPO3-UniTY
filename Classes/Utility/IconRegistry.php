<?php

namespace WebVision\WvT3unity\Utility;

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IconRegistry
 * @package WebVision\WvT3unity\Utility
 */
class IconRegistry
{

    public static function registerIcons()
    {
        $iconRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $contentElementIconFilePrefix = 'EXT:wv_t3unity/Resources/Public/Icons/';


        $iconRegistry->registerIcon(
            'tx-wvt3unity-clear-cache',
            BitmapIconProvider::class,
            ['source' => $contentElementIconFilePrefix . 'magento_clear_cache_icon.png']
        );
    }
}