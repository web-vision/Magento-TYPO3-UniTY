<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
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
    'version' => '4.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '7.0.0-8.99.99',
            'realurl' => '2.0.0-2.99.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
];
