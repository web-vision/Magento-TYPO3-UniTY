<?php
namespace WebVision\WvT3unity\Hooks;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Tim Werdin  <t.werdin@web-vision.de>, web-vision GmbH
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

use WebVision\WvT3unity\Utility\Configuration;

/**
 * This class renders all meta data as json
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 */
class ContentPostProc extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin
{
    public function hookEntry(&$params, &$that)
    {
        if (Configuration::isMagentoContent($params['pObj']->type, 'head')) {
            $this->removeGenerator($params['pObj']->content);
            $this->parseMetaTags($params['pObj']->content);
            $this->parseCss($params['pObj']->content);
            $this->parseJs($params['pObj']->content);

            $params['pObj']->content = preg_replace('/,\s?]/', ']', $params['pObj']->content);
        }
    }

    protected function removeGenerator(&$content)
    {
        $content = preg_replace('/<meta name="generator".*?>/', '', $content);
    }

    protected function parseMetaTags(&$content)
    {
        $content = preg_replace_callback(
            '/<meta (name|property)="(.*?)" content="(.*?)" ?\/?>/s',
            array($this, 'metaCallback'),
            $content
        );
    }

    protected function parseCss(&$content)
    {
        $content = preg_replace('/<link rel=".*?" type=".*?" href="(.*?)" media=".*?"\s*\/{0,1}>/', '"$1",', $content);
    }

    protected function parseJs(&$content)
    {
        $content = preg_replace('/<script( src="(.*?)")? type=".*?" ?\/?>(<\/script>)?/', '"$2",', $content);
    }

    public function metaCallback($matches)
    {
        $matches[3] = str_replace(array("\r\n", "\n"), ' ', $matches[3]);

        return '{"' . $matches[1] . '": "' . $matches[2] . '", "content":"' . $matches[3] . '"},';
    }
}
