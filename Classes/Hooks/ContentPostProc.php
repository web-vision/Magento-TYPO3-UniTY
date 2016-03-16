<?php
namespace WebVision\WvT3unity\Hooks;

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

use WebVision\WvT3unity\Utility\Configuration;

/**
 * This class renders all meta data as json
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 */
class ContentPostProc extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin
{
    /**
     * This method get's called by the hook and will parse the html head data into a
     * json.
     *
     * @param array $params
     * @param mixed $that
     */
    public function hookEntry(array &$params, &$that)
    {
        if (Configuration::isMagentoContent($params['pObj']->type, 'head')) {
            $this->removeGenerator($params['pObj']->content);
            $this->parseMetaTags($params['pObj']->content);
            $this->parseCss($params['pObj']->content);
            $this->parseJs($params['pObj']->content);

            $params['pObj']->content = preg_replace('/,\s?]/', ']', $params['pObj']->content);
        }
    }

    /**
     * This method removes the meta tags with name generator.
     *
     * @param string $content The content to parse.
     */
    protected function removeGenerator(&$content)
    {
        $content = preg_replace('/<meta name="generator".*?>/', '', $content);
    }

    /**
     * This method parses meta tags with a name or property attribute into a json
     *
     * @param string $content The content to parse.
     */
    protected function parseMetaTags(&$content)
    {
        $content = preg_replace_callback(
            '/<meta (name|property)="(.*?)" content="(.*?)" ?\/?>/s',
            array($this, 'metaCallback'),
            $content
        );
    }

    /**
     * This method replaces link tags with the value of the href attribute.
     *
     * @param string $content The content to parse.
     */
    protected function parseCss(&$content)
    {
        $content = preg_replace('/<link rel=".*?" type=".*?" href="(.*?)" media=".*?"\s*\/{0,1}>/', '"$1",', $content);
    }

    /**
     * This method replaces script tags with the value of the src attribute.
     *
     * @param string $content The content to parse.
     */
    protected function parseJs(&$content)
    {
        $content = preg_replace('/<script( src="(.*?)")? type=".*?" ?\/?>(<\/script>)?/', '"$2",', $content);
    }

    /**
     * Helper method used as callback for preg_replace_callback to parse the matches
     * into a json.
     *
     * @param array $matches The matches of the preg_replace_callback method.
     *
     * @return string The generated json.
     */
    public function metaCallback(array $matches)
    {
        $matches[3] = str_replace(array("\r\n", "\n"), ' ', $matches[3]);

        return '{"' . $matches[1] . '": "' . $matches[2] . '", "content":"' . $matches[3] . '"},';
    }
}
