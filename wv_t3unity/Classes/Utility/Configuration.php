<?php

namespace WebVision\WvT3unity\Utility;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

/**
 * web-vision GmbH
 *
 * NOTICE OF LICENSE
 *
 * <!--LICENSETEXT-->
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.web-vision.de for more information.
 *
 * @category    WebVision
 * @package     WebVision_Ipponshop
 * @copyright   Copyright (c) 2001-2015 web-vision GmbH (http://www.web-vision.de)
 * @license     <!--LICENSEURL-->
 * @author      Tim Werdin <t.werdin@web-vision.de>
 */
class Configuration extends ConfigurationManager
{
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
            'menu'
        );

        if($unityType !== null) {
            if(in_array($unityType, $unityTypes)) {
                $unityTypes = array($unityType);
            }
        }

        $unityTypeNums = array();
        foreach($unityTypes as $unityType) {
            if(($config = $GLOBALS['TSFE']->tmpl->setup['tx_wvt3unity_' . $unityType . '.']['typeNum']) !== null) {
                $unityTypeNums[] = $config;
            }
        }

        return in_array($pageType, $unityTypeNums);
    }
}
