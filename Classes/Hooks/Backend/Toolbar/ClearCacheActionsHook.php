<?php
namespace WebVision\WvT3unity\Hooks\Backend\Toolbar;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Class ClearCacheActionsHook
 *
 * @package WebVision\WvT3unity\Hooks\Backend\Toolbar
 */
class ClearCacheActionsHook implements ClearCacheActionsHookInterface
{
    /**
     * Modifies CacheMenuItems array
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers 
     *        (typically  used by userTS with options.clearCache.identifier)
     *
     * @return void
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        $languagePath = 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang.xlf:';

        if ($this->getBackendUser()->isAdmin()) {
            $cacheActions[] = [
                'id' => 'wvt3unity_clear_magento_cache',
                'title' => $languagePath . 'toolbar.clearCacheTitle',
                'description' => $languagePath . 'toolbar.clearCacheDescription',
                'href' => BackendUtility::getModuleUrl(
                    'tce_db', 
                    [
                        'vC' => $this->getBackendUser()->veriCode(), 
                        'cacheCmd' => 'wvt3unity_clear_magento_cache', 
                        'ajaxCall' => 1
                    ]
                ),
                'iconIdentifier' => 'tx-wvt3unity-clear-cache'
            ];

            $optionValues[] = 'wvt3unity_clear_magento_cache';
        }
    }

    /**
     * Returns the current BE user.
     *
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

}