<?php
namespace WebVision\WvT3unity\Utility;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;

/**
 * Class IconRegistry
 *
 * @package WebVision\WvT3unity\Utility
 */
class IconRegistry
{
    public static function registerIcons()
    {
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        $contentElementIconFilePrefix = 'EXT:wv_t3unity/Resources/Public/Icons/';

        $iconRegistry->registerIcon(
            'tx-wvt3unity-clear-cache',
            BitmapIconProvider::class,
            [
                'source' => $contentElementIconFilePrefix . 'magento_clear_cache_icon.png'
            ]
        );
    }
}