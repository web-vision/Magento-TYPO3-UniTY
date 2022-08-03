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
    'author_company' => 'web-vision GmbH',
    'state' => 'alpha',
    'version' => '4.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.9.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
];
