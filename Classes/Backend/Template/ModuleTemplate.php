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
    private function addPaths($to, array $paths) {
        if (property_exists($this, $to . 'RootPaths')) {
            foreach ($paths as $path) {
                array_push($this->{$to . 'RootPaths'}, $path);
            }
        }
    }

    protected function addTemplateRootPaths(array $templatePaths) {
        $this->addPaths('template', $templatePaths);
    }

    protected function addPartialRootPaths(array $partialPaths) {
        $this->addPaths('partial', $partialPaths);
    }

    protected function addLayoutRootPaths(array $layoutPaths) {
        $this->addPaths('layout', $layoutPaths);
    }

    protected function setTemplateFile($templateFile) {
        $this->templateFile = $templateFile;
    }

    private function modifyProperty(&$modify, array $params) {
        foreach ($params as $method => $arguments) {
            if (method_exists($modify, $method)) {
                \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump([$arguments], '[$arguments]');
                call_user_func_array([$modify, $method], [$arguments]);
            } else {
                throw new \InvalidArgumentException(
                    '"' . $method . '" do not exist in "' . get_class($modify) . '".'
                );
            }
        }
    }

    public function modifyModuleTemplate(array $params) {
        $this->modifyProperty($this, $params);

        return $this;
    }

    public function modifyModuleTemplateView(array $params) {
        $this->modifyProperty($this->view, $params);

        return $this;
    }
}