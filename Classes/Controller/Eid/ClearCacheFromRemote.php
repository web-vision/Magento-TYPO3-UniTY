<?php
namespace WebVision\WvT3unity\Controller\Eid;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClearCacheFromRemote
 *
 * @package WebVision\WvT3unity\Eid
 */
class ClearCacheFromRemote
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function clearCacheAction(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $command = GeneralUtility::_GP('cmd');

        /** @var  \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler */
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->admin = true;

        try {
            switch ($command) {
                case 'pages':
                    $dataHandler->clear_cacheCmd('pages');
                    break;
                default:
                    throw new \InvalidArgumentException();
                    break;
            }
        } catch(\Exception $exception) {
            $response = $response->withStatus(500);
        }

        return $response;
    }
}