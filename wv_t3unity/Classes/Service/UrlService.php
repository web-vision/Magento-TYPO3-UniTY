<?php
namespace WebVision\WvT3unity\Service;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Kai Ole Hartwig <o.hartwig@web-vision.de>, web-vision GmbH
 *           Tim Werdin  <t.werdin@web-vision.de>, web-vision GmbH
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class UrlService provides some PHP functionality to detect
 * the current URL
 */
class UrlService {

    /**
     * Returns the URL for the current webpage
     *
     * @param $content string The content (usually empty)
     * @param $conf array The TypoScript configuration
     * @return string the canonical URL of this page
     */
    public function getCanonicalUrl($content, $conf) {
        if ($this->getFrontendController()->page['canonical_url']) {
            $url = $this->getFrontendController()->page['canonical_url'];
        } else {
            $pageId = $this->getFrontendController()->id;
            $pageType = $this->getFrontendController()->type;
            $mountPointInUse = FALSE;
            $MP = '';

            if ($this->getFrontendController()->MP) {
                $mountPointInUse = TRUE;
                $GLOBALS['TYPO3_CONF_VARS']['FE']['enable_mount_pids'] = 0;
                $MP = $this->getFrontendController()->MP;
                $this->getFrontendController()->MP = '';
            }

            $configuration = array(
                'parameter' => $pageId . ',' . $pageType,
                'addQueryString' => 1,
                'addQueryString.' => array(
                    'method' => 'GET',
                    'exclude' => 'MP'
                ),
                'forceAbsoluteUrl' => 1
            );
            $url = $this->getFrontendController()->cObj->typoLink_URL($configuration);
            $url = $this->getFrontendController()->baseUrlWrap($url);

            if ($mountPointInUse) {
                $this->getFrontendController()->MP = $MP;
                $GLOBALS['TYPO3_CONF_VARS']['FE']['enable_mount_pids'] = 1;
            }

        }

        if ($url) {
            $urlParts = parse_url($url);
            $scheme = $urlParts['scheme'];
            if (isset($conf['useDomain'])) {
                if ($conf['useDomain'] == 'current') {
                    $domain = GeneralUtility::getIndpEnv('HTTP_HOST');
                } else {
                    $domain = $conf['useDomain'];
                }
                if (!$scheme) {
                    $scheme = 'http';
                }
                $url =  $scheme . '://' . $domain . $urlParts['path'];
            } elseif (empty($urlParts['scheme'])) {
                $pageWithDomains = $this->getFrontendController()->findDomainRecord();
                // get first domain record of that page
                $allDomains = $this->getFrontendController()->sys_page->getRecordsByField(
                    'sys_domain',
                    'pid', $pageWithDomains,
                    'AND redirectTo = ""' . $this->getFrontendController()->sys_page->enableFields('sys_domain'),
                    '',
                    'sorting ASC'
                );
                if (!empty($allDomains)) {
                    $domain = (GeneralUtility::getIndpEnv('TYPO3_SSL') ? 'https://' : 'http://');
                    $domain = $domain . $allDomains[0]['domainName'];
                    $domain = rtrim($domain, '/') . '/' . GeneralUtility::getIndpEnv('TYPO3_SITE_PATH');
                } else {
                    $domain = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
                }
                $url = rtrim($domain, '/') . '/' . ltrim($url, '/');
            }
            // remove everything after the ?
            list($url) = explode('?', $url);
        }
        return $url;
    }

    /**
     * wrapper function for the current TSFE object
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getFrontendController() {
        return $GLOBALS['TSFE'];
    }
}