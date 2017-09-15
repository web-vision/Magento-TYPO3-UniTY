<?php
declare(strict_types=1);
namespace WebVision\WvT3unity\Http;

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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Http\RequestHandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use WebVision\WvT3unity\Authentication\BackendUserAuthentication;
use WebVision\WvT3unity\Service\ModulesService;

/**
 * Class SsoRequestHandler
 */
class RequestHandler implements RequestHandlerInterface
{
    /**
     * Instance of the current TYPO3 bootstrap
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * Constructor handing over the bootstrap and the original request
     *
     * @param Bootstrap $bootstrap
     */
    public function __construct(Bootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * Handles any backend request
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function handleRequest(ServerRequestInterface $request)
    {
        $request = $request->withQueryParams(array_merge($request->getQueryParams(), ['standalone' => '1']));
        $queryParams = $request->getQueryParams();

        // Set login data
        $_POST['username'] = (string)$queryParams['username'];
        $_POST['userident'] = 'huselpusel:fnordberg';
        $_POST['login_status'] = 'login';

        $this->boot();

        if (!empty($queryParams['module'])) {
            $moduleName = GeneralUtility::makeInstance(ModulesService::class)->getRealModuleNameFromMapping($queryParams['module']);
        } else {
            $moduleName = (string)$queryParams['M'];
        }

        if ($moduleName === '') {
            throw new \InvalidArgumentException(
                'Module must be set to be able to perform further dispatching',
                1505302795
            );
        }

        // Remove values from query params that are not required anymore
        $valuesToBeRemoved = ['module', 'M', 'username', 'token'];
        $queryParams = array_diff_key($queryParams, array_flip($valuesToBeRemoved));

        HttpUtility::redirect(BackendUtility::getModuleUrl($moduleName, $queryParams));
        exit;
    }

    /**
     * Does the main work for setting up the backend environment for any Backend request
     */
    protected function boot()
    {
        $this->bootstrap
            ->checkLockedBackendAndRedirectOrDie()
            ->checkBackendIpOrDie()
            ->checkSslBackendAndRedirectIfNeeded()
            ->initializeBackendRouter()
            ->loadBaseTca()
            ->loadExtTables()
            ->initializeBackendUser(BackendUserAuthentication::class)
            ->initializeBackendAuthentication(true)
            ->initializeLanguageObject()
            ->initializeBackendTemplate()
            ->endOutputBufferingAndCleanPreviousOutput()
            ->initializeOutputCompression()
            ->sendHttpHeaders();
    }

    /**
     * This request handler can handle any backend request (but not CLI).
     *
     * @param ServerRequestInterface $request
     * @return bool If the request is not a CLI script, TRUE otherwise FALSE
     */
    public function canHandleRequest(ServerRequestInterface $request)
    {
        return TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_BE && !(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_CLI);
    }

    /**
     * Returns the priority - how eager the handler is to actually handle the request.
     *
     * @return int The priority of the request handler.
     */
    public function getPriority(): int
    {
        return 95;
    }
}
