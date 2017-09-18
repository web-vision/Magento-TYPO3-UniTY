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

use TYPO3\CMS\Backend\Controller\BackendController as T3BackendController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use WebVision\WvT3unity\Service\ModulesService;

/**
 * Overrides BackendController to redirect to standalone view
 * if requested. 
 */
class BackendController extends T3BackendController
{
    /**
     * Injects the request object for the current request or subrequest
     * As this controller goes only through the render() method, it is rather simple for now
     *
     * @param ServerRequestInterface $request the current request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface the response with the content
     */
    public function mainAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        // @WVTODO: Check for "$request->getQueryParams()['module']"
        if (isset($_COOKIE['module'])) {
            $modulesService = GeneralUtility::makeInstance(ModulesService::class);

            HttpUtility::redirect(
                BackendUtility::getModuleUrl(
                    $modulesService->getRealModuleNameFromMapping($_COOKIE['module']),
                    [
                        'standalone' => true,
                    ]
                )
            );
        } else {
            return parent::mainAction($request, $response);
        }
    }
}
