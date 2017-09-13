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

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Controller\LoginController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Service for handling a requested module.
 */
class ModulesService
{
    const MODULE_NOT_CONFIGURED = 'MODULE_NOT_CONFIGURED';
    const INVALID_URL = 'INVALID_URL';

    /**
     * @var string
     */
    protected $module = '';

    /**
     * Handles the request for a specific backend module.
     *
     * @return void
     */
    public function handleModuleRequest(
        LoginController &$loginController, 
        ServerRequestInterface $request
    ) {
        $this->module = $request->getQueryParams()['module'];

        if (empty($this->module)) {
            return;
        }

        if (! $this->moduleIsConfigured($this->module)) {
            $this->writeLog($request, self::MODULE_NOT_CONFIGURED);

            return;
        }

        if (! $this->modifyRedirectUrl($loginController, $this->module)) {
            $this->writeLog($request, self::INVALID_URL);

            return;
        }
    }

    /**
     * Checks if requested module is configured.
     *
     * @return bool
     */
    protected function moduleIsConfigured($module) 
    {
        return (
            in_array(
                $module, 
                $this->getModuleRequestConfiguration()['allowedModules']
            ) ? true : false
        );
    }

    /**
     * Modifies the redirect URL after successful login if
     * a specific module is requested.
     *
     * @return bool
     */
    protected function modifyRedirectUrl(
        LoginController &$loginController, 
        $module
    ) {
        $sanitizedLocalUrl = GeneralUtility::sanitizeLocalUrl(
            $loginController->getRedirectToURL() . '&module=' . $module
        );

        if (! empty($sanitizedLocalUrl)) {
            $loginController->setRedirectUrl($sanitizedLocalUrl);
            $loginController->setRedirectToURL($sanitizedLocalUrl);

            setcookie('module', $module, 0, '/typo3/');

            return true;
        }

        return false;
    }

    /**
     * @param ServerRequestInterface $request
     * @param int $logKey
     *
     * @return void
     */
    private function writeLog(ServerRequestInterface $request, $logKey) 
    {
        $remoteAddress = $request->getServerParams()['REMOTE_ADDR'];

        switch ($logKey) {
            case self::MODULE_NOT_CONFIGURED:
                GeneralUtility::sysLog(
                    'The requested module "' . $this->module . '" is not allowed or configured (from ' . 
                        $remoteAddress . ' ).', 
                    'wv_t3unity', 
                    GeneralUtility::SYSLOG_SEVERITY_NOTICE
                );
                break;
            case self::INVALID_URL:
                GeneralUtility::sysLog(
                    'The URL "' . $url . '" is not considered to be local and was denied ' .
                        'for module "' . $this->module . '" (from ' . $remoteAddress . ' ).', 
                    'wv_t3unity', 
                    GeneralUtility::SYSLOG_SEVERITY_NOTICE
                );
                break;
        }
    }

    /**
     * @param string $module
     *
     * @return string
     * @throws \InvalidArgumentException if the requested module is no
     * configured alias for a module.
     */
    public function getRealModuleNameFromMapping($module) {
        $moduleConfiguration = $this->getModuleRequestConfiguration();

        if (isset($moduleConfiguration['moduleMapping'])) {
            foreach ($moduleConfiguration['moduleMapping'] as $realModule => $moduleAlias) {
                if (in_array($module, $moduleAlias)) {
                    return $realModule;
                }
            }
        }

        throw new \InvalidArgumentException(
            'Requested module "' . $module . '" has no mapping for a real module key.'
        );
    }

    /**
     * Wrapper for MODULE_REQUESTS configuration.
     *
     * @return array 
     */
    public function getModuleRequestConfiguration() 
    {
        return (is_array($GLOBALS['MODULE_REQUESTS']) ? $GLOBALS['MODULE_REQUESTS'] : []);
    }
}
