<?php

namespace WebVision\WvT3unity\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use WebVision\WvT3unity\Service\ClearCacheService;


/**
 * Class DataHandler
 * @package WebVision\WvT3unity\Hooks
 */
class DataHandler
{

    /**
     * @param $command
     */
    public function clearAdditionalCache($command)
    {

        /**
         * @var \WebVision\WvT3unity\Service\ClearCacheService;
         */
        $clearCacheService = GeneralUtility::makeInstance(ClearCacheService::class);

        switch ($command['cacheCmd']) {
            case 'wvt3unity_clear_magento_cache':
                $clearCacheService->clearCacheForAllPages();
                break;

            default:
                if (MathUtility::canBeInterpretedAsInteger($command['cacheCmd'])) {
                    $pageUid = (int)$command['cacheCmd'];
                    $clearCacheService->clearCacheForSinglePage($pageUid);
                }
                break;
        }
    }

}
