<?php
namespace WebVision\WvT3unity\Utility;

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
