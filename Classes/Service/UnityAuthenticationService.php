<?php
declare(strict_types=1);
namespace WebVision\WvT3unity\Service;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Sv\AuthenticationService;

/**
 * Authenticates
 */
class UnityAuthenticationService extends AuthenticationService
{
    /**
     * Standard prefix id for the service
     * Same as class name
     *
     * @var string
     */
    public $prefixId = 'tx_t3unity_sv1';

    /**
     * Path to this script relative to the extension dir.
     *
     * @var string
     */
    public $scriptRelPath = 'Classes/Service/AuthenticationService.php';

    /**
     * The extension key
     *
     * @var string
     */
    public $extKey = 'wv_t3unity';

    /**
     * Find a user (eg. look up the user record in database when a login is sent)
     *
     * @return mixed User array or FALSE
     */
    public function getUser()
    {
        if ($this->login['status'] !== 'login') {
            return false;
        }

        $user = $this->fetchUserRecord($this->login['uname']);

        if (
            $user['tx_t3unity_standalone'] && 
            (string)$this->login['uident_text'] === ''
        ) {
            // Failed Login attempt (no password given)
            $this->writelog(
                255, 3, 3, 2, 
                'Login-attempt from %s (%s) for username \'%s\' with an empty password!', 
                [
                    $this->authInfo['REMOTE_ADDR'], 
                    $this->authInfo['REMOTE_HOST'], 
                    $this->login['uname']
                ]
            );

            GeneralUtility::sysLog(
                sprintf(
                    'Login-attempt from %s (%s), for username \'%s\' with an empty password!', 
                    $this->authInfo['REMOTE_ADDR'], 
                    $this->authInfo['REMOTE_HOST'], 
                    $this->login['uname']
                ), 
                'Core', 
                GeneralUtility::SYSLOG_SEVERITY_WARNING
            );

            return false;
        }

        if (! is_array($user)) {
            // Failed login attempt (no username found)
            $this->writelog(
                255, 3, 3, 2, 
                'Login-attempt from %s (%s), username \'%s\' not found!!', 
                [
                    $this->authInfo['REMOTE_ADDR'], 
                    $this->authInfo['REMOTE_HOST'], 
                    $this->login['uname']
                ]
            );

            // Logout written to log
            GeneralUtility::sysLog(
                sprintf(
                    'Login-attempt from %s (%s), username \'%s\' not found!', 
                    $this->authInfo['REMOTE_ADDR'], 
                    $this->authInfo['REMOTE_HOST'], 
                    $this->login['uname']
                ), 
                'core', 
                GeneralUtility::SYSLOG_SEVERITY_WARNING
            );
        } else {
            if ($this->writeDevLog) {
                GeneralUtility::devLog(
                    'User found: ' . GeneralUtility::arrayToLogString(
                        $user, 
                        [
                            $this->db_user['userid_column'], 
                            $this->db_user['username_column']
                        ]
                    ), 
                    self::class
                );
            }
        }
        return $user;
    }

    /**
     * Method adds a further authUser method.
     *
     * Will return one of following authentication status codes:
     * - 0 - authentication failure
     * - 100 - just go on. User is not authenticated but there is still no reason to stop
     * - 200 - the service was able to authenticate the user
     *
     * @param array $user Array containing FE user data of the logged user.
     *
     * @return int Authentication status code, one of 0,100 and 200
     */
    public function authUser(array $user): int
    {
        if ($user['tx_t3unity_standalone']) {
            // This is a standalone user, redirect to next authentication service
            return 100;
        }

        if (!GeneralUtility::_GET('token')) {
            return 100;
        }

        if ($this->validateSession($user['username'], GeneralUtility::_GET('token'))) {
            return 200;
        }

        return 0;
    }

    /**
     * @param string $username
     * @param string $token
     * @return bool
     */
    protected function validateSession(string $username, string $token): bool
    {
        $extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wv_t3unity']);
        $domain = rtrim($extensionConfiguration['MagentoUrl'], '/');

        $requestUrl = $domain . '/rest/V1/unity/validateToken/username/' . 
            urlencode($username) . '/token/' . urlencode($token);

        try {
            $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
            $response = $requestFactory->request($requestUrl);
        } catch (\Exception $e) {
            $this->getLogger()->critical($e->getMessage());
            return false;
        }

        return (string)$response->getBody()->getContents() === 'true';
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
    }
}
