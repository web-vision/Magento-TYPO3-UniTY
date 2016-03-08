<?php
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Tim Werdin  <t.werdin@web-vision.de>, web-vision GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
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
