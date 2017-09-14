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

use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
        $this->standalonePageTreeView = GeneralUtility::makeInstance(standalonePageTreeView::class);
        $this->standalonePageTreeView->setStandaloneMode(true)->init();
        $this->standalonePageTreeView->getTree(0);
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
                    $this->standalonePageTreeView->printTree(),
                ]
            ]
        );
    }
}