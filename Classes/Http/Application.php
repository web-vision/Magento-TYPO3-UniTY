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
use TYPO3\CMS\Core\Core\ApplicationInterface;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Http\ServerRequestFactory;

/**
 * Entry point for the TYPO3 Backend (HTTP requests)
 */
class Application implements ApplicationInterface
{
    /**
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * Number of subdirectories where the entry script is located, relative to PATH_site
     * Usually this is equal to PATH_site = 0
     *
     * @var int
     */
    protected $entryPointLevel = 6;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * All available request handlers that can handle backend requests (non-CLI)
     *
     * @var array
     */
    protected $availableRequestHandlers = [
        RequestHandler::class,
    ];

    /**
     * Constructor setting up legacy constant and register available Request Handlers
     *
     * @param \Composer\Autoload\ClassLoader $classLoader an instance of the class loader
     */
    public function __construct($classLoader)
    {
        $this->defineLegacyConstants();

        $this->bootstrap = Bootstrap::getInstance()
            ->initializeClassLoader($classLoader)
            ->setRequestType(TYPO3_REQUESTTYPE_BE | (!empty($_GET['ajaxID']) ? TYPO3_REQUESTTYPE_AJAX : 0))
            ->baseSetup($this->entryPointLevel)
        ;

        foreach ($this->availableRequestHandlers as $requestHandler) {
            $this->bootstrap->registerRequestHandlerImplementation($requestHandler);
        }

        $this->bootstrap->configure();
    }

    /**
     * Set up the application and shut it down afterwards
     *
     * @param callable $execute
     */
    public function run(callable $execute = null)
    {
        $this->request = ServerRequestFactory::fromGlobals();
        $this->bootstrap->handleRequest($this->request);

        if ($execute !== null) {
            call_user_func($execute);
        }

        $this->bootstrap->shutdown();
    }

    /**
     * Define constants and variables
     */
    protected function defineLegacyConstants()
    {
        define('TYPO3_MODE', 'BE');
    }
}
