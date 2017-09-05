<?php

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Unity',
    'description' => 'Connects TYPO3 with Magento',
    'category' => 'services',
    'author' => 'Tim Werdin, Yannick Hermes',
    'author_email' => 't.werdin@web-vision.de, y.hermes@web-vision.de',
    'state' => 'alpha',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '3.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '7.0.0-8.99.99',
            'realurl' => '2.0.0-2.99.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
];
