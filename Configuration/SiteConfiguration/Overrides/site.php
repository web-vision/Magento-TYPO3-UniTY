<?php

$GLOBALS['SiteConfiguration']['site']['columns']['magentoUrl'] = [
    'label' => 'Magento URL',
    'description' => 'Will be used in Context Menu to open the page in Magento',
    'config' => [
        'type' => 'input',
        'eval' => 'trim',
    ],
];

$GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] = str_replace(
    'base,',
    'base, magentoUrl, ',
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem']
);
