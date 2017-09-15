<?php
namespace WebVision\WvT3unity\Hooks;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use WebVision\WvT3unity\Service\ClearCacheService;

/**
 * Class DataHandler
 *
 * @package WebVision\WvT3unity\Hooks
 */
class DataHandler
{
    /**
     * @param $command
     */
    public function clearAdditionalCache($command)
    {
        $clearCacheService = GeneralUtility::makeInstance(ClearCacheService::class);

        switch ($command['cacheCmd']) {
            case 'wvt3unity_clear_magento_cache':
                $clearCacheService->clearCacheForAllPages();
                break;
            default:
                if (MathUtility::canBeInterpretedAsInteger($command['cacheCmd'])) {
                    $clearCacheService->clearCacheForSinglePage(
                        (int)$command['cacheCmd']
                    );
                }
                break;
        }
    }

}
