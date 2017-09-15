<?php

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
        if (TYPO3_MODE == "BE") {
            \WebVision\WvT3unity\Utility\IconRegistry::registerIcons();
        }
    },
    'wv_t3unity'
);