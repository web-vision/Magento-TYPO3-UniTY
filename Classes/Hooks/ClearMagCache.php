<?php
namespace WebVision\WvT3unity\Hooks;

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

use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use WebVision\WvT3unity\Xclass\SimpleDataHandlerController;

/**
 *
 * @author Anu Bhuvanendran Nair <anu@web-vision.de>
 */
class ClearMagCache implements ClearCacheActionsHookInterface
{

    /**
     * Modifies CacheMenuItems array
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     *
     * @return void
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {

        $cacheActions[] = [
            'id' => 'magento',
            'title' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang.xlf:flushMagCachesTitle',
            'description' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang.xlf:flushMagCachesDescription',
            'href' => BackendUtility::getModuleUrl('tce_db', ['cacheCmd' => 'magento']),
            'iconIdentifier' => 'actions-system-cache-clear-impact-medium',
        ];
        $this->optionValues[] = 'magento';

    }
    
}
