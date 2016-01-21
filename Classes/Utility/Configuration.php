<?php
namespace WebVision\WvT3unity\Utility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Tim Werdin  <t.werdin@web-vision.de>, web-vision GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

/**
 * This class provides some helper functions for configuration values.
 *
 * @author      Tim Werdin <t.werdin@web-vision.de>
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

        $unityTypes = array(
            'head',
            'page',
            'column',
            'element',
            'menu',
        );

        if ($unityType !== null) {
            if (in_array($unityType, $unityTypes)) {
                $unityTypes = array($unityType);
            }
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
