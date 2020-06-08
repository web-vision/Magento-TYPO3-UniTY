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
use TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Class responsible for providing click menu items for db records which don't have custom provider (as e.g. pages)
 */
class PageProvider extends \TYPO3\CMS\Backend\ContextMenu\ItemProviders\PageProvider
{
    /**
     * Additional attributes for the 'view' item
     *
     * @return array
     */
    protected function getViewAdditionalAttributes(): array
    {
        $attributes = [];
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        // Collecting the extension option to check whether redirect have to made
        $configurationUtility = $objectManager->get(ConfigurationUtility::class);
        $extensionConfiguration = $configurationUtility->getCurrentConfiguration('wv_t3unity');
        $magePreview = $extensionConfiguration['magPagePreview']['value'];
        // Magento preview enabled
        if ($magePreview == 1) {
            $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
            $tsSetting = $configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
            );
            // Collecting the magento URL saved in TS object
            $magUrl = $tsSetting['lib.']['magurlValue.']['value'];
            $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
            $urlSegment = $pageRepository->getPage($this->getPreviewPid())['slug'];
            
            if ($urlSegment != NULL) {
                // Writing the redirect URL with magento baseURL and with a trailing slash
                $viewLink = rtrim($magUrl, "/") . $urlSegment . '/';
            } else {
                $viewLink = $this->getViewLink();
            }
            
        } else {
            // Magento preview disabled and vanilla URL generation
            $viewLink = $this->getViewLink();
        }
        if ($viewLink) {
            $attributes += [
                'data-preview-url' => htmlspecialchars($viewLink),
            ];
        }
        return $attributes;
    }
}
