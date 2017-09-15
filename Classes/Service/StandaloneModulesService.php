<?php
namespace WebVision\WvT3unity\Service;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WebVision\WvT3unity\Backend\Template\ModuleTemplate;
use WebVision\WvT3unity\Backend\Tree\View\StandalonePageTreeView;

/**
 * Service for handling standalone modules.
 */
class StandaloneModulesService
{
    /**
     * @var StandalonePageTreeView
     */
    protected $standalonePageTreeView = null;

    /**
     * @var array
     */
    protected $templateRootPaths = [ 'EXT:wv_t3unity/Resources/Private/Templates/Backend/' ];

    /**
     * @var array
     */
    protected $layoutRootPaths = [ 'EXT:wv_t3unity/Resources/Private/Layouts/Backend/' ];

    /**
     * @var array
     */
    protected $partialRootPaths = [ 'EXT:wv_t3unity/Resources/Private/Partials/Backend/' ];

    /**
     * @var string
     */
    protected $templateFile = 'StandaloneModule.html';

    /**
     * Constructor.
     */
    public function __construct() {
        $this->standalonePageTreeView = GeneralUtility::makeInstance(StandalonePageTreeView::class);
        $this->standalonePageTreeView->setStandaloneMode(true)->init('AND ' . $this->getBackendUserAuthentication()->getPagePermsClause(Permission::PAGE_SHOW));
    }

    /**
     * @param ModuleTemplate $moduleTemplate
     *
     * @return ModuleTemplate
     */
    public function setStandaloneParams(ModuleTemplate $moduleTemplate)
    {
        return $moduleTemplate->modifyModuleTemplates(
            [
                'templateRootPaths' => [$this->templateRootPaths],
                'layoutRootPaths' => [$this->layoutRootPaths],
                'partialRootPaths' => [$this->partialRootPaths],
                'templateFile' => [$this->templateFile]
            ]
        )->modifyModuleTemplateView(
            [
                'assign' => [
                    'pageTree',
                    $this->standalonePageTreeView->getBrowsableTree(),
                ]
            ]
        );
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}