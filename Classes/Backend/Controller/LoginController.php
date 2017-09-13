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

use TYPO3\CMS\Backend\Controller\LoginController as T3LoginController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WebVision\WvT3unity\Service\ModulesService;

/**
 */
class LoginController extends T3LoginController
{
    /**
     * Injects the request and response objects for the current request or subrequest
     * As this controller goes only through the main() method, it is rather simple for now
     *
     * @param ServerRequestInterface $request the current request
     * @param ResponseInterface $response the current response
     * @return ResponseInterface the finished response with the content
     */
    public function formAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $modulesService = GeneralUtility::makeInstance(ModulesService::class);
        $modulesService->handleModuleRequest($this, $request);
        
        return parent::formAction($request, $response);
    }

    /**
     * @return string
     */
    public function getRedirectUrl() {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return LoginController
     */
    public function setRedirectUrl($redirectUrl) {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectToURL() {
        return $this->redirectToURL;
    }

    /**
     * @param string $redirectToURL
     *
     * @return LoginController
     */
    public function setRedirectToURL($redirectToURL) {
        $this->redirectToURL = $redirectToURL;

        return $this;
    }
}
