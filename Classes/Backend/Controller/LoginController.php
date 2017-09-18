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

use TYPO3\CMS\Backend\Controller\LoginController as T3LoginController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WebVision\WvT3unity\Service\ModulesService;

/**
 * Extends Backend's LoginController to handle requests for
 * standalone modules.
 */
class LoginController extends T3LoginController
{
    /**
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
