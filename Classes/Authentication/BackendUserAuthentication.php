<?php
namespace WebVision\WvT3unity\Authentication;

/**
 * TYPO3 backend user authentication
 * Contains most of the functions used for checking permissions, authenticating users,
 * setting up the user, and API for user from outside.
 * This class contains the configuration of the database fields used plus some
 * functions for the authentication process of backend users.
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
