<?php

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'] = [
    '_DEFAULT' => [
        'init' => [
            'appendMissingSlash' => 'ifNotFile,redirect',
            'emptyUrlReturnValue' => '/cms/',
        ],
        'pagePath' => [
            'rootpage_id' => '1',
        ],
        'fileName' => [
            'defaultToHTMLsuffixOnPrev' => 1,
            'acceptHTMLsuffix' => 1,
        ],
        'postVarSets' => [
            '_DEFAULT' => [
                'news' => [
                    [
                        'GETvar' => 'tx_news_pi1[action]',
                        'valueMap' => [
                            'detail' => '',
                        ],
                        'noMatch' => 'bypass',
                    ],
                    [
                        'GETvar' => 'tx_news_pi1[controller]',
                        'valueMap' => [
                            'News' => '',
                        ],
                        'noMatch' => 'bypass',
                    ],
                    [
                        'GETvar' => 'tx_news_pi1[news]',
                        'lookUpTable' => [
                            'table' => 'tx_news_domain_model_news',
                            'id_field' => 'uid',
                            'alias_field' => 'concat(title,"-",uid)',
                            'useUniqueCache' => 1,
                            'useUniqueCache_conf' => [
                                'strtolower' => 1,
                                'spaceCharacter' => '-',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
);
