<?php
namespace WebVision\WvT3unity\Service;

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

use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for handling standalone modules.
 */
class StandaloneModulesService
{
    /**
     * @var array
     */
    protected $templateRootPaths = [
        'EXT:wv_t3unity/Resources/Private/Templates/Backend/',
    ];

    /**
     * @var array
     */
    protected $layoutRootPaths = [
        'EXT:wv_t3unity/Resources/Private/Layouts/Backend/',
    ];

    /**
     * @var array
     */
    protected $partialRootPaths = [
        'EXT:wv_t3unity/Resources/Private/Partials/Backend/',
    ];

    protected $templateFile = 'StandaloneModule.html';

    /**
     * @param ModuleTemplate $moduleTemplate
     * 
     * @return ModuleTemplate
     */
    public function setStandaloneParams(ModuleTemplate $moduleTemplate) 
    {
        foreach ($this->templateRootPaths as $templateRootPath) {
            $moduleTemplate->addTemplateRootPath($templateRootPath);
        }

        foreach ($this->layoutRootPaths as $layoutRootPath) {
            $moduleTemplate->addLayoutRootPath($layoutRootPath);
        }

        foreach ($this->partialRootPaths as $partialRootPath) {
            $moduleTemplate->addPartialRootPath($partialRootPath);
        }

        $moduleTemplate->setTemplateFile($this->templateFile);

        $moduleTemplate->updateView();

        return $moduleTemplate;
    }
}