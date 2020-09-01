<?php
namespace WebVision\WvT3unity\Middleware;

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
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Initialization of TypoScriptFrontendController
 * Do all necessary preparation steps for rendering
* @author Ricky Mathew <ricky@web-vision.de>
 */
class PrepareTypoScriptFrontendRendering extends \TYPO3\CMS\Frontend\Middleware\PrepareTypoScriptFrontendRendering
{
    /**
     * Initialize TypoScriptFrontendController to the point right before rendering of the page is triggered
     * Overrided to Check for request which calls only for menus and specific static contents
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // as long as TSFE throws errors with the global object, this needs to be set, but
        // should be removed later-on once TypoScript Condition Matcher is built with the current request object.
        $GLOBALS['TYPO3_REQUEST'] = $request;
        // Get from cache
        $this->timeTracker->push('Get Page from cache');
        $queryParams = $request->getQueryParams();
        // Checking for request which calls only for menus and specific static contents
        // Page contents will still be rendered from cache 
        if (!( array_key_exists("uid", $queryParams) || array_key_exists("special", $queryParams) || array_key_exists("colPos", $queryParams)) ) {
            $this->controller->getFromCache();
        }
        $this->timeTracker->pull();
        // Get config if not already gotten
        // After this, we should have a valid config-array ready
        $this->controller->getConfigArray();

        // Setting language and locale
        $this->timeTracker->push('Setting language');
        $this->controller->settingLanguage();
        $this->timeTracker->pull();

        // Convert POST data to utf-8 for internal processing if metaCharset is different
        if ($this->controller->metaCharset !== 'utf-8' && $request->getMethod() === 'POST') {
            $parsedBody = $request->getParsedBody();
            if (is_array($parsedBody) && !empty($parsedBody)) {
                $this->convertCharsetRecursivelyToUtf8($parsedBody, $this->controller->metaCharset);
                $request = $request->withParsedBody($parsedBody);
            }
        }
        return $handler->handle($request);
    }
}
