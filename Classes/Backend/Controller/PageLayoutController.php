<?php
namespace WebVision\WvT3unity\Backend\Controller;

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

use \TYPO3\CMS\Backend\Controller\PageLayoutController as BackendPageLayoutController;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \WebVision\WvT3unity\Service\StandaloneModulesService;
use \TYPO3\CMS\Backend\Tree\View\PageTreeView;

/**
 * Renders Web > Page module standalone with pagetree.
 */
class PageLayoutController extends BackendPageLayoutController
{
    /**
     * @param ServerRequestInterface $request the current request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface the response with the content
     */
    public function mainAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $GLOBALS['SOBE'] = $this;
        $this->init();

        if ($request->getQueryParams()['standalone'] == 1) {
            $standaloneModulesService = GeneralUtility::makeInstance(StandaloneModulesService::class);
            $standaloneModulesService->setStandaloneParams($this->moduleTemplate);
        }

        $this->clearCache();
        $this->main();
        
        $response->getBody()->write(
            $this->moduleTemplate->renderContent()
        );

        return $response;
    }
}
