<?php
$EM_CONF[$_EXTKEY] = array(
    'title'            => 'Unity',
    'description'      => 'Connects TYPO3 with Magento',
    'category'         => 'services',
    'author'           => 'Kai Ole Hartwig, Tim Werdin ',
    'author_email'     => 'o.hartwig@web-vision.de, t.werdin@web-vision.de',
    'state'            => 'alpha',
    'internal'         => '',
    'uploadfolder'     => '0',
    'createDirs'       => '',
    'clearCacheOnLoad' => 0,
    'version'          => '2.0.0',
    'constraints'      => array(
        'depends'   => array(
            'typo3'   => '6.2.0-7.99.99',
            'realurl' => '0.0.0-0.0.0',
        ),
        'conflicts' => array(),
        'suggests'  => array(),
    ),
);
