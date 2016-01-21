<?php
namespace WebVision\WvT3unity\ViewHelpers\News;

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
     * @param bool                       $uriOnly       Return only the url without the a-tag
     * @param array                      $configuration Optional typolink configuration
     * @param string                     $content       Optional content which is linked
     * @param bool                       $forceBaseUrl  Optional forces %BASE_URL% instead of %TYPO3_URL%
     *
     * @return string Link
     */
    public function render(\Tx_News_Domain_Model_News $newsItem, array $settings = array(), $uriOnly = false, array $configuration = array(), $content = '', $forceBaseUrl = false)
    {
        $return = parent::render($newsItem, $settings, $uriOnly, $configuration, $content);

        if ($forceBaseUrl && Configuration::isMagentoContent()) {
            $return = str_replace('%TYPO3_URL%', '%BASE_URL%', $return);
        }

        return $return;
    }
}
