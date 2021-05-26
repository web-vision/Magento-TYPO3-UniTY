<?php
namespace WebVision\WvT3unity\Utility;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2020 web-vision GmbH
 */

 use Psr\Http\Message\ResponseInterface;
 use Psr\Http\Message\ServerRequestInterface;
 use TYPO3\CMS\Core\Http\RedirectResponse;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Backend\Routing\UriBuilder;
 use WebVision\WvT3unity\Xclass\SimpleDataHandlerController;

/**
 * This utility class checks when the mage cache clear button is clicked and clears cache base on API
 *
 */
class ClearCustomCache
{
    /**
     * @param ServerRequestInterface $request
     *
     */
    public static function clear(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getQueryParams()['page'];
        $redirectModule = $request->getQueryParams()['redirectModule'];

        $backendUriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uriParameters = ['id' => $id];
        $redirectLink = $backendUriBuilder->buildUriFromRoute(
            $redirectModule,
            $uriParameters
        );

        $simpleDataHandler = GeneralUtility::makeInstance(SimpleDataHandlerController::class);
        $mageUrl = $simpleDataHandler->getMagUrl();
        $url = rtrim($mageUrl, "/") . '/rest/V1/unity/clearAllCaches/cacheType/blocks';
        $result = file_get_contents($url);
        return new RedirectResponse($redirectLink);
    }
}
