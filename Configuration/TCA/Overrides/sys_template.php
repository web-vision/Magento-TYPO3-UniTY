<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */
call_user_func(
    function ($extKey) {
        ExtensionManagementUtility::addStaticFile(
            $extKey,
            'Configuration/TypoScript/',
            'web-vision UniTY - Config'
        );
    },
    'wv_t3unity'
);
