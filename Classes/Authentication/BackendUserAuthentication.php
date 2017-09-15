<?php
declare(strict_types=1);
namespace WebVision\WvT3unity\Authentication;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

/**
 * Unity SSO user authentication
 * Enables authentication via GET parameter
 */
class BackendUserAuthentication extends \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
{
    /**
     * Setting this flag TRUE lets user-authentication happen from GET_VARS if
     * POST_VARS are not set. Thus you may supply username/password with the URL.
     * @var bool
     */
    public $getMethodEnabled = true;
}
