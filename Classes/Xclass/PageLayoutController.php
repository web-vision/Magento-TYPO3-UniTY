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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Http\HtmlResponse;
use WebVision\WvT3unity\Xclass\SimpleDataHandlerController;

/**
 *
 * @author Anu Bhuvanendran Nair <anu@web-vision.de>
 */
class PageLayoutController extends \TYPO3\CMS\Backend\Controller\PageLayoutController
{

    /**
     * Injects the request object for the current request or subrequest
     * As this controller goes only through the main() method, it is rather simple for now
     *
     * @param ServerRequestInterface $request the current request
     * @return ResponseInterface the response with the content
     */
    public function mainAction(ServerRequestInterface $request): ResponseInterface
    {
        parent::mainAction($request);
        $simpleDataHandler = GeneralUtility::makeInstance(SimpleDataHandlerController::class);
        $mageUrl = $simpleDataHandler->getMagUrl();
        // Check if url contains block_cache clear param 
        // If present do the needful
        if (GeneralUtility::_GP('clear_magebcache') == 1) {
            $url = rtrim($mageUrl, "/") . '/rest/V1/unity/clearAllCaches/cacheType/blocks';
            $result = file_get_contents($url);
            if ($result != 'true') {
                echo 'false';
                echo '<br/>';
            }
        }
        $this->main($request);
        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    /***************************
     *
     * Sub-content functions, rendering specific parts of the module content.
     *
     ***************************/
    /**
     * This creates the buttons for the modules
     */
    protected function makeButtons(ServerRequestInterface $request): void
    {
        parent::makeButtons($request);
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $simpleDataHandler = GeneralUtility::makeInstance(SimpleDataHandlerController::class);
        $mageUrl = $simpleDataHandler->getMagUrl();
        $uriBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
        // Building the button for magento block_cache clearing
        $mageCacheClearButton = $this->buttonBar->makeLinkButton()
            ->setHref($uriBuilder->buildUriFromRoute($this->moduleName, ['id' => $this->pageinfo['uid'], 'clear_magebcache' => '1']))
            ->setIcon($iconFactory->getIcon('actions-system-cache-clear-impact-medium', Icon::SIZE_SMALL))
            ->setTitle('Clear Magento Block Cache');
        $this->buttonBar->addButton($mageCacheClearButton, ButtonBar::BUTTON_POSITION_RIGHT, 2);
    }

}
