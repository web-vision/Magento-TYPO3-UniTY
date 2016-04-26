<?php
namespace WebVision\WvT3unity\ViewHelpers\News;

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
 * ViewHelper to render news url for UniTY usage.
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 */
class LinkViewHelper extends \Tx_News_ViewHelpers_LinkViewHelper
{
    /**
     * Render link to news item or internal/external pages
     *
     * @param \Tx_News_Domain_Model_News $newsItem      Current news object
     * @param array                      $settings
     * @param bool                       $uriOnly       Return only the url
     * @param array                      $configuration Optional typolink config
     * @param string                     $content       Optional content to link
     * @param bool                       $forceBaseUrl  Optional forces %BASE_URL%
     *
     * @return string Link
     */
    public function render(
        \Tx_News_Domain_Model_News $newsItem,
        array $settings = array(),
        $uriOnly = false,
        array $configuration = array(),
        $content = '',
        $forceBaseUrl = false
    ) {
        $return = parent::render($newsItem, $settings, $uriOnly, $configuration, $content);

        if ($forceBaseUrl && Configuration::isMagentoContent()) {
            $return = str_replace('%TYPO3_URL%', '%BASE_URL%', $return);
        }

        return $return;
    }
}
