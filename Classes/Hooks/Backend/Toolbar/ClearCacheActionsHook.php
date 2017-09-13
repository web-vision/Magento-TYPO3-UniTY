<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 22.04.15
 * Time: 15:52
 */

namespace WebVision\WvT3unity\Hooks\Backend\Toolbar;

use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;


/**
 * Class ClearCacheActionsHook
 * @package WebVision\WvT3unity\Hooks\Backend\Toolbar
 */
class ClearCacheActionsHook implements ClearCacheActionsHookInterface
{

    /**
     * Modifies CacheMenuItems array
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     *
     * @return void
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {

        if ($this->getBackendUser()->isAdmin()) {
            $cacheActions[] = array(
              'id' => 'wvt3unity_clear_magento_cache',
              'title' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang.xlf:toolbar.clearCacheTitle',
              'description' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang.xlf:toolbar.clearCacheDescription',
              'href' => BackendUtility::getModuleUrl('tce_db', ['vC' => $this->getBackendUser()->veriCode(), 'cacheCmd' => 'wvt3unity_clear_magento_cache', 'ajaxCall' => 1]),
              'iconIdentifier' => 'tx-wvt3unity-clear-cache'
            );
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