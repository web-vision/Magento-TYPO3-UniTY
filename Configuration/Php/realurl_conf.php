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
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'] = array(
    '_DEFAULT' => array(
        'init'        => array(
            'enableCHashCache'     => true,
            'appendMissingSlash'   => 'ifNotFile,redirect',
            'adminJumpToBackend'   => true,
            'enableUrlDecodeCache' => true,
            'enableUrlEncodeCache' => true,
            'emptyUrlReturnValue'  => '/',
        ),
        'pagePath'    => array(
            'type'           => 'user',
            'userFunc'       => 'EXT:realurl/class.tx_realurl_advanced.php:&tx_realurl_advanced->main',
            'spaceCharacter' => '-',
            'languageGetVar' => 'L',
            'rootpage_id'    => '1',
        ),
        'fileName'    => array(
            'defaultToHTMLsuffixOnPrev' => 1,
            'acceptHTMLsuffix'          => 1,
        ),
        'postVarSets' => array(
            '_DEFAULT' => array(
                'news' => array(
                    array(
                        'GETvar'   => 'tx_news_pi1[action]',
                        'valueMap' => array(
                            'detail' => '',
                        ),
                        'noMatch'  => 'bypass',
                    ),
                    array(
                        'GETvar'   => 'tx_news_pi1[controller]',
                        'valueMap' => array(
                            'News' => '',
                        ),
                        'noMatch'  => 'bypass',
                    ),
                    array(
                        'GETvar'      => 'tx_news_pi1[news]',
                        'lookUpTable' => array(
                            'table'               => 'tx_news_domain_model_news',
                            'id_field'            => 'uid',
                            'alias_field'         => 'concat(title,"-",uid)',
                            'useUniqueCache'      => 1,
                            'useUniqueCache_conf' => array(
                                'strtolower'     => 1,
                                'spaceCharacter' => '-',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
