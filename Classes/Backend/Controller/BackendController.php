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

use TYPO3\CMS\Backend\Controller\BackendController as T3BackendController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageTreeView;
use WebVision\WvT3unity\Service\ModulesService;

/**
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
        //if (! empty($request->getQueryParams()['module'])) {
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
