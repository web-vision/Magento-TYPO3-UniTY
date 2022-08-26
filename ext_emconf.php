<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Unity',
    'description' => 'Connects TYPO3 with Magento',
    'category' => 'services',
    'author' => 'Tim Werdin, Yannick Hermes',
    'author_email' => 't.werdin@web-vision.de, y.hermes@web-vision.de',
    'author_company' => 'web-vision GmbH',
    'state' => 'beta',
    'version' => '4.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.9.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
];
