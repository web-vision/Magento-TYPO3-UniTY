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

use \TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

/**
 * This class provides some helper functions for configuration values.
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 */
class Configuration extends ConfigurationManager
{
    /**
     * @param null|int $pageType
     * @param null|string $unityType
     *
     * @return bool
     */
    public static function isMagentoContent($pageType = null, $unityType = null)
    {
        if ($pageType === null) {
            $pageType = $GLOBALS['TSFE']->type;
        }

        $unityTypes = [
            'Head',
            'Page',
            'Column',
            'Element',
            'Menu',
        ];

        if ($unityType !== null && in_array($unityType, $unityTypes)) {
            $unityTypes = [$unityType];
        }

        $unityTypeNums = [];
        foreach ($unityTypes as $unityType) {
            if (($config = $GLOBALS['TSFE']->tmpl->setup['Unity' . $unityType . '.']['typeNum']) !== null) {
                $unityTypeNums[] = $config;
            }
        }

        return in_array($pageType, $unityTypeNums);
    }
}
