<?php
namespace WebVision\WvT3unity\Recordlist;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\RecordList\RecordList as CoreRecordList;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \WebVision\WvT3unity\Service\StandaloneModulesService;
use \TYPO3\CMS\Backend\Tree\View\PageTreeView;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Renders Web > List module standalone with pagetree.
 */
class RecordList extends CoreRecordList
{
    /**
     * @param ServerRequestInterface $request the current request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface the response with the content
     */
    public function mainAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        BackendUtility::lockRecords();
        $GLOBALS['SOBE'] = $this;
        $this->init();

        if ($request->getQueryParams()['standalone'] == 1) {
            $standaloneModulesService = GeneralUtility::makeInstance(StandaloneModulesService::class);
            $standaloneModulesService->setStandaloneParams($this->moduleTemplate);
        }
        
        $this->clearCache();
        $this->main();

        $this->moduleTemplate->setContent(
            $this->content
        );

        $response->getBody()->write(
            $this->moduleTemplate->renderContent()
        );

        return $response;
    }
}
