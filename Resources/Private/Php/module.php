<?php
/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

// Exit early if php requirement is not satisfied.
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    die('This version of TYPO3 CMS requires PHP 7.0 or above');
}

// Set up the application for the backend
call_user_func(function () {
    $classLoader = require realpath(__DIR__ . '/../../../../../../typo3/../vendor/autoload.php');
    require __DIR__ . '/../../../Classes/Http/Application.php';

    (new \WebVision\WvT3unity\Http\Application($classLoader))->run();
});
