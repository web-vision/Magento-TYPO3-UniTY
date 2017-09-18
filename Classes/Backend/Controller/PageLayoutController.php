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
use TYPO3\CMS\Backend\Module\BaseScriptClass;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\Icon;
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

    /**
     * This creates the buttons for the modules
     */
    protected function makeButtons()
    {
        $module = $this->getModule();
        $lang = $this->getLanguageService();

        // New record on pages that are not locked by editlock
        if (!$module->modTSconfig['properties']['noCreateRecordsLink'] && $this->editLockPermissions()) {
            $href = BackendUtility::getModuleUrl('db_new', [
                'id' => $this->id,
                'returnUrl' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            ]);

            $createButton = $this->buttonBar->makeLinkButton()
                ->setHref($href)
                ->setTitle($lang->getLL('newRecordGeneral'))
                ->setIcon($this->iconFactory->getIcon('actions-document-new', Icon::SIZE_SMALL));

            $this->buttonBar->addButton($createButton);
        }

        parent::makeButtons();
    }

    /**
     * @return BaseScriptClass
     */
    protected function getModule()
    {
        return $GLOBALS['SOBE'];
    }

    /**
     * Check whether or not the current backend user is an admin or the current page is
     * locked by editlock.
     *
     * @return bool
     */
    protected function editLockPermissions(): bool
    {
        return $this->getBackendUserAuthentication()->isAdmin() || !$this->pageinfo['editlock'];
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
