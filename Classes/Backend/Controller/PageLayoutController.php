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
use \TYPO3\CMS\Backend\Tree\View\PageTreeView;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \WebVision\WvT3unity\Service\StandaloneModulesService;

/**
 * Script Class for Web > Layout module
 */
class PageLayoutController extends BackendPageLayoutController
{
    /**
     * Injects the request object for the current request or subrequest
     * As this controller goes only through the main() method, it is rather simple for now
     *
     * @param ServerRequestInterface $request the current request
     * @param ResponseInterface $response
     * @return ResponseInterface the response with the content
     */
    public function mainAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $GLOBALS['SOBE'] = $this;
        $this->init();

        if ($request->getQueryParams()['standalone'] == 1) {
            $this->moduleTemplate = GeneralUtility::makeInstance(
                StandaloneModulesService::class
            )->setStandaloneParams($this->moduleTemplate);

            $pageTreeView = GeneralUtility::makeInstance(PageTreeView::class);
            $pageTreeView->setStandaloneMode(true)->init();
            $pageTreeView->getTree(0);

            $view = $this->moduleTemplate->getView();
            $view->assign(
                'pageTree', $pageTreeView->printTree()
            );

            $this->moduleTemplate->setView($view);
        }        

        $this->clearCache();
        $this->main();
        $response->getBody()->write(
            $this->moduleTemplate->renderContent()
        );

        return $response;
    }
}
