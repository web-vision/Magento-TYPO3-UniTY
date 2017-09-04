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
