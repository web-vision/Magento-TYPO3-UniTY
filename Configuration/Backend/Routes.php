<?php
use WebVision\WvT3unity\Backend\Controller;

/**
 * Definitions for routes provided by wv_t3unity.
 */
return [
    // Login screen of the TYPO3 Backend
    'login' => [
        'path' => '/login',
        'access' => 'public',
        'target' => Controller\LoginController::class . '::formAction'
    ],
    'main' => [
        'path' => '/main',
        'target' => Controller\BackendController::class . '::mainAction'
    ],
];
