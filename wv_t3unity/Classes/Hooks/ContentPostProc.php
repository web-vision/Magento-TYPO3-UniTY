<?php
namespace WebVision\WvT3unity\Hooks;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Kai Ole Hartwig <o.hartwig@web-vision.de>, web-vision GmbH
 *           Tim Werdin  <t.werdin@web-vision.de>, web-vision GmbH
 *           Justus Moroni <j.moroni@web-vision.de>, web-vision GmbH
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

class ContentPostProc extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin
{
    public function contentPostProcAll(&$params, &$that)
    {
        if( $params['pObj']->type == $GLOBALS['TSFE']->tmpl->setup['tx_wvt3unity_head.']['typeNum'] ) {
            $this->_removeGenerator($params['pObj']->content);
            $this->_parseMetaTags($params['pObj']->content);
            $this->_parseCss($params['pObj']->content);
            $this->_parseJs($params['pObj']->content);

            $params['pObj']->content = str_replace(',]', ']', $params['pObj']->content);
        }
    }

    private function _removeGenerator(&$content)
    {
        $content = preg_replace('/<meta name="?generator"?.+?>/is', '', $content);
    }

    private function _parseMetaTags(&$content)
    {
        $content = preg_replace('/<meta (name|property)="(.*?)" content="(.*?)" ?\/?>/', '{"$1": "$2", "content":"$3"},', $content);
    }
    private function _parseCss(&$content)
    {
        $content = preg_replace('/<link rel="(.*?)" type="(.*?)" href="(.*?)" media="(.*?)">/',
            '{"href": "$3"},', $content);
    }
    private function _parseJs(&$content)
    {
        $content = preg_replace('/<script( src="(.*?)")? type="(.*?)">(.*?)<\/script>/',
            '{"src": "$2", "content": "$4"},', $content);
    }
}
