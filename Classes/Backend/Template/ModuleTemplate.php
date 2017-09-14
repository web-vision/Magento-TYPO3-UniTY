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
 * Extends Backend's ModuleTemplate to override template paths
 * and update view according to those changes.
 */
class ModuleTemplate extends BackendModuleTemplate
{
    /**
     * @param string $to
     * @param array $paths
     *
     * @return void
     */
    private function addPaths($to, array $paths) {
        if (property_exists($this, $to . 'RootPaths')) {
            foreach ($paths as $path) {
                array_push($this->{$to . 'RootPaths'}, $path);
            }
        }
    }

    /**
     * @param array $templatePaths
     *
     * @return array | ModuleTemplate
     */
    public function templateRootPaths(array $templatePaths = []) {
        if (empty($templatePaths)) {
            return $this->templateRootPaths;
        }

        $this->addPaths('template', $templatePaths);

        return $this;
    }

    /**
     * @param array $partialRootPaths
     *
     * @return array | ModuleTemplate
     */
    public function partialRootPaths(array $partialPaths = []) {
        if (empty($partialPaths)) {
            return $this->partialRootPaths;
        }

        $this->addPaths('partial', $partialPaths);
        
        return $this;
    }

    /**
     * @param array $layoutRootPaths
     *
     * @return array | ModuleTemplate
     */
    public function layoutRootPaths(array $layoutPaths = []) {
        if (empty($layoutPaths)) {
            return $this->layoutRootPaths;
        }

        $this->addPaths('layout', $layoutPaths);

        return $this;
    }

    /**
     * @param string $templateFile
     *
     * @return string | ModuleTemplate
     */
    public function templateFile($templateFile = '') {
        if ($templateFile) {
            $this->templateFile = $templateFile;
            
            return $this;
        }

        return $this->templateFile;
    }

    /**
     * @param mixed $modify
     * @param array $params
     *
     * @throws \InvalidArgumentException If key of $params is no method of $modify
     * @return mixed
     */
    private function modifyProperty($modify, array $params) {
        foreach ($params as $method => $arguments) {
            if (method_exists($modify, $method)) {
                $modify = $modify->{$method}(...$arguments);
            } else {
                throw new \InvalidArgumentException(
                    '"' . $method . '" do not exist in "' . get_class($modify) . '".'
                );
            }
        }

        return $modify;
    }

    /**
     * @param array $params
     *
     * @return ModuleTemplate
     */
    public function modifyModuleTemplates(array $params) {
        $this->modifyProperty($this, $params);

        return $this;
    }

    /**
     * @param array $params
     *
     * @return ModuleTemplate
     */
    public function modifyModuleTemplateView(array $params) {
        $this->modifyProperty($this->view, $params);
        $this->updateView();

        return $this;
    }

    /**
     * @return void
     */
    protected function updateView() {
        $this->view->setTemplateRootPaths($this->templateRootPaths);
        $this->view->setLayoutRootPaths($this->layoutRootPaths);
        $this->view->setPartialRootPaths($this->partialRootPaths);
        $this->view->setTemplate($this->templateFile);
    }
}