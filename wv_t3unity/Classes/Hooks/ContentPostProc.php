<?php
namespace WebVision\WvT3unity\Hooks;

use WebVision\WvT3unity\Utility\Configuration;

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
    public function contentPostProc(&$params, &$that)
    {
        if (Configuration::isMagentoContent($params['pObj']->type, 'head')) {
            $this->_removeGenerator($params['pObj']->content);
            $this->_parseMetaTags($params['pObj']->content);
            $this->_parseCss($params['pObj']->content);
            $this->_parseJs($params['pObj']->content);

            $params['pObj']->content = preg_replace('/,\s?]/', ']', $params['pObj']->content);
        }
    }

    protected function _removeGenerator(&$content)
    {
        $content = preg_replace('/<meta name="generator".*?>/', '', $content);
    }

    protected function _parseMetaTags(&$content)
    {
        $content = preg_replace_callback('/<meta (name|property)="(.*?)" content="(.*?)" ?\/?>/s', array($this, 'metaCallback'), $content);
    }

    protected function _parseCss(&$content)
    {
        $content = preg_replace('/<link rel=".*?" type=".*?" href="(.*?)" media=".*?">/', '"$1",', $content);
    }

    protected function _parseJs(&$content)
    {
        $content = preg_replace('/<script( src="(.*?)")? type=".*?" ?\/?>(<\/script>)?/', '"$2",', $content);
    }

    function metaCallback($matches)
    {
        $matches[3] = str_replace(array("\r\n", "\n"), ' ', $matches[3]);

        return '{"' . $matches[1] . '": "' . $matches[2] . '", "content":"' . $matches[3] . '"},';
    }
}
