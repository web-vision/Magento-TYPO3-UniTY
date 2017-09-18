<?php
namespace WebVision\WvT3unity\Backend\Controller;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use \TYPO3\CMS\Backend\Controller\PageLayoutController as BackendPageLayoutController;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \WebVision\WvT3unity\Service\StandaloneModulesService;

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
