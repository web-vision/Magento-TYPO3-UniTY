<?php
namespace WebVision\WvT3unity\Xclass;

/*
 * Copyright (c) 2020 web-vision GmbH
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published byd
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 *
 * @author Anu Bhuvanendran Nair <anu@web-vision.de>
 */
class SimpleDataHandlerController extends \TYPO3\CMS\Backend\Controller\SimpleDataHandlerController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Executing the posted actions ...
     */
    public function main()
    {
        // Identifying the magento cache clear command
        if ($this->cacheCmd == 'magento') {
            $this->clearCache();
        }
        parent::main();
    }

    /**
     * Clear magento cache via the API call...
     */
    public function clearCache()
    {
        // Initializing configuration manager for reading
        // TS settings
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
        $tsSetting = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        // Collecting the magento URL saved in TS object
        $magUrl = $tsSetting['lib.']['magurlValue.']['value'];

        if ($magUrl == null) {
            echo 'The magento URL is not saved in TypoScript settings.';
        } else {
            $url = rtrim($magUrl, "/") . '/rest/V1/unity/clearAllCaches';
            $result = file_get_contents($url);
            if ($result != 'true') {
                echo 'false';
            }
        }
    }

}
