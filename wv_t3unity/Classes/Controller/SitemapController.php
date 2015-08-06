<?php
namespace WebVision\WvT3unity\Controller;

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

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * This package includes all functions for generating XML sitemaps
 *
 */
class SitemapController {

    /**
     * holds all configuration information for rendering the sitemap
     * @var array
     */
    protected $sitemapConfiguration = array();

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $_db;

    /**
     * @var array
     */
    protected $_tree = array();

    /**
     * Generates a XML sitemap from the page structure, entry point for the page
     *
     * @param string $content the content to be filled, usually empty
     * @param array $configuration additional configuration parameters given via TypoScript
     * @return string the XML sitemap ready to render
     */
    public function renderXMLSitemap($content, $configuration) {
        $this->sitemapConfiguration = $configuration;

        $this->_db = $GLOBALS['TYPO3_DB'];

        $id = (int)$this->getFrontendController()->id;
        $sysLanguageUid = (int)$this->getFrontendController()->sys_language_uid;
        $treeRecords = $this->_getTreeList($id, $sysLanguageUid);

        if(array_key_exists('excludeUid', $this->sitemapConfiguration)) {
            $excludedPageUids = GeneralUtility::trimExplode(',', $this->sitemapConfiguration['excludeUid'], true);
        } else {
            $excludedPageUids = array();
        }

        $usedUrls = array();
        foreach ($treeRecords as $item) {
            // don't render spacers, sysfolders etc
            if ($item['doktype'] >= 199) {
                continue;
            }

            // remove "hide-in-menu" items
            if (intval($item['nav_hide']) == 1) {
                continue;
            }

            // remove pages from excludeUid list
            if (in_array($item['uid'], $excludedPageUids)) {
                continue;
            }

            // get translations if available
            if(array_key_exists('lang', $item) && $item['lang']) {
                $item = $item['lang'];
            }


            $url = $item['canonical_url'] ? $item['canonical_url'] : $item['unity_path'];
            $realUrl = $item['canonical_url'] ? $item['unity_path'] : '';
            $lastmod = $item['SYS_LASTCHANGED'] ? $item['SYS_LASTCHANGED'] : $item['crdate'];

            $lastmod = date('c', $lastmod);

            $usedUrls[$item['uid']] = array(
                'url' => ltrim($url, '/'),
                'real_url' => ltrim($realUrl, '/'),
                'lastmod' => $lastmod,
            );
        }

        $content = json_encode($usedUrls);

        return $content;
    }

    /**
     * fetches the pages needed from the tree component
     *
     * @param int $pid
     * @param int $sysLanguageUid
     * @return array
     */
    protected function _getTreeList($pid, $sysLanguageUid, $prefix = '') {
        $fields = 'uid,doktype,crdate,unity_path,canonical_url';

        $id = (int)$pid;
        if ($id < 0) {
            $id = abs($id);
        }
        if ($id) {
            $resultSet = $this->_db->exec_SELECTquery($fields . ',mount_pid,nav_hide,SYS_LASTCHANGED', 'pages', 'pid=' . $id . ' ' . BackendUtility::deleteClause('pages'));
            while ($row = $this->_db->sql_fetch_assoc($resultSet)) {
                $row['unity_path'] = $prefix . $row['unity_path'];
                $this->_tree[$row['uid']] = $row;

                if($sysLanguageUid) {
                    // get localized data
                    $langResultSet = $this->_db->exec_SELECTquery($fields, 'pages_language_overlay', 'pid=' . $row['uid'] . ' ' . BackendUtility::deleteClause('pages_language_overlay') . ' AND sys_language_uid = ' . $sysLanguageUid);
                    $langResult = $this->_db->sql_fetch_assoc($langResultSet);
                    $langResult['unity_path'] = $prefix . $langResult['unity_path'];
                    $this->_tree[$row['uid']]['lang'] = $langResult;
                }

                // get children
                if($row['doktype'] == 7 && $row['mount_pid']) {
                    $sub_prefix = preg_replace('/\.html$/', '', $row['unity_path']);
                    $this->_getTreeList($row['mount_pid'], $sysLanguageUid, $sub_prefix);
                } else {
                    $this->_getTreeList($row['uid'], $sysLanguageUid, $prefix);
                }
            }
        }
        return $this->_tree;
    }

    /**
     * wrapper function for the current TSFE object
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getFrontendController() {
        return $GLOBALS['TSFE'];
    }
}
