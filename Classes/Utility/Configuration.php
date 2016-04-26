<?php
namespace WebVision\WvT3unity\Utility;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

/**
 * This class provides some helper functions for configuration values.
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 */
class Configuration extends ConfigurationManager
{
    /**
     * @param null|int    $pageType
     * @param null|string $unityType
     *
     * @return bool
     */
    public static function isMagentoContent($pageType = null, $unityType = null)
    {
        if ($pageType === null) {
            $pageType = $GLOBALS['TSFE']->type;
        }

        $unityTypes = array(
            'head',
            'page',
            'column',
            'element',
            'menu',
        );

        if ($unityType !== null && in_array($unityType, $unityTypes)) {
            $unityTypes = array($unityType);
        }

        $unityTypeNums = array();
        foreach ($unityTypes as $unityType) {
            if (($config = $GLOBALS['TSFE']->tmpl->setup['tx_wvt3unity_' . $unityType . '.']['typeNum']) !== null) {
                $unityTypeNums[] = $config;
            }
        }

        return in_array($pageType, $unityTypeNums);
    }
}
