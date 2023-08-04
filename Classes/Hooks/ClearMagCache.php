<?php
namespace WebVision\WvT3unity\Hooks;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 * @author Anu Bhuvanendran Nair <anu@web-vision.de>
 *
 * @ToDo: Migrate to PSR-14-based ModifyClearCacheActionsEvent
 */
class ClearMagCache implements ClearCacheActionsHookInterface
{

    /**
     * Modifies CacheMenuItems array
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues): void
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $cacheActions[] = [
            'id' => 'magento',
            'title' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang.xlf:flushMagCachesTitle',
            'description' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang.xlf:flushMagCachesDescription',
            'href' => $uriBuilder->buildUriFromRoute('tce_db', ['cacheCmd' => 'magento']),
            'iconIdentifier' => 'actions-system-cache-clear-impact-medium',
        ];
        $this->optionValues[] = 'magento';
    }
}
