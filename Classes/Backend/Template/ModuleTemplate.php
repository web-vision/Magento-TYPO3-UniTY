<?php
namespace WebVision\WvT3unity\Backend\Template;

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

use TYPO3\CMS\Backend\Template\ModuleTemplate as BackendModuleTemplate;

/**
 */
class ModuleTemplate extends BackendModuleTemplate
{
    public function addTemplateRootPath($templatePath) {
        array_push($this->templateRootPaths, $templatePath);

        return $this;
    }

    public function addPartialRootPath($partialPath) {
        array_push($this->partialRootPaths, $partialPath);

        return $this;
    }

    public function addLayoutRootPath($layoutPath) {
        array_push($this->layoutRootPaths, $layoutPath);
        
        return $this;
    }

    public function setTemplateFile($templateFile) {
        $this->templateFile = $templateFile;
        
        return $this;
    }

    public function updateView() {
        $this->view->setPartialRootPaths($this->partialRootPaths);
        $this->view->setTemplateRootPaths($this->templateRootPaths);
        $this->view->setLayoutRootPaths($this->layoutRootPaths);
        $this->view->setTemplate($this->templateFile);
    }

    public function setView($view) {
        $this->view = $view;

        return $this;
    }
}