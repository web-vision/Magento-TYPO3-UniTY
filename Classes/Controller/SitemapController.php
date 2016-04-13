<?php
namespace WebVision\WvT3unity\Controller;

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

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * This class includes all functions for generating XML sitemaps.
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 */
class SitemapController
{
    const COLUMN_UNITY_PATH    = 'unity_path';
    const COLUMN_CANONICAL_URL = 'canonical_url';

    /**
     * Holds all configuration information for rendering the sitemap.
     *
     * @var array
     */
    protected $sitemapConfiguration = array();

    /**
     * Holds the database class for easier access throughout the class.
     *
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $db;

    /**
     * Page tree for the current page.
     *
     * @var array
     */
    protected $tree = array();

    /**
     * Generates a XML sitemap from the page structure, entry point for the page
     *
     * @param string $content       The content to be filled, usually empty
     * @param array  $configuration Additional configuration parameters given via TypoScript
     *
     * @return string The XML sitemap ready to render
     */
    public function renderXmlSitemap($content, array $configuration)
    {
        $this->sitemapConfiguration = $configuration;

        $this->db = $GLOBALS['TYPO3_DB'];

        $id = (int)$this->getFrontendController()->id;
        $sysLanguageUid = (int)$this->getFrontendController()->sys_language_uid;
        $treeRecords = $this->getTreeList($id, $sysLanguageUid);

        if (array_key_exists('excludeUid', $this->sitemapConfiguration)) {
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
            if (array_key_exists('lang', $item) && $item['lang']) {
                $item = $item['lang'];
            }

            $url = $item[static::COLUMN_CANONICAL_URL] ? $item[static::COLUMN_CANONICAL_URL] : $item[static::COLUMN_UNITY_PATH];
            $realUrl = $item[static::COLUMN_CANONICAL_URL] ? $item[static::COLUMN_UNITY_PATH] : '';
            $lastmod = $item['SYS_LASTCHANGED'] ? $item['SYS_LASTCHANGED'] : $item['crdate'];

            $lastmod = date('c', $lastmod);

            $usedUrls[$item['uid']] = array(
                'url'      => ltrim($url, '/'),
                'real_url' => ltrim($realUrl, '/'),
                'lastmod'  => $lastmod,
            );
        }

        return json_encode($usedUrls);
    }

    /**
     * Fetches the pages needed from the tree component.
     *
     * @param int    $pid            The pid to start with.
     * @param int    $sysLanguageUid The language uid.
     * @param string $prefix         The prefix for the path.
     *
     * @return array
     */
    protected function getTreeList($pid, $sysLanguageUid, $prefix = '')
    {
        $fields = 'uid,doktype,crdate,unity_path,canonical_url';

        $id = (int)$pid;
        if ($id < 0) {
            $id = abs($id);
        }
        if ($id) {
            $resultSet = $this->db->exec_SELECTquery(
                $fields . ',mount_pid,nav_hide,SYS_LASTCHANGED',
                'pages',
                'pid=' . $id . ' ' . BackendUtility::deleteClause('pages')
            );
            while (($row = $this->db->sql_fetch_assoc($resultSet))) {
                $row[static::COLUMN_UNITY_PATH] = $prefix . $row[static::COLUMN_UNITY_PATH];
                $this->tree[$row['uid']] = $row;

                if ($sysLanguageUid) {
                    // get localized data
                    $langResultSet = $this->db->exec_SELECTquery(
                        $fields,
                        'pages_language_overlay',
                        'pid='
                        . $row['uid']
                        . ' '
                        . BackendUtility::deleteClause('pages_language_overlay')
                        . ' AND sys_language_uid = '
                        . $sysLanguageUid
                    );
                    $langResult = $this->db->sql_fetch_assoc($langResultSet);
                    $langResult[static::COLUMN_UNITY_PATH] = $prefix . $langResult[static::COLUMN_UNITY_PATH];
                    $this->tree[$row['uid']]['lang'] = $langResult;
                }

                // get children
                if ($row['doktype'] == 7 && $row['mount_pid']) {
                    $subPrefix = preg_replace('/\.html$/', '', $row[static::COLUMN_UNITY_PATH]);
                    $this->getTreeList($row['mount_pid'], $sysLanguageUid, $subPrefix);
                } else {
                    $this->getTreeList($row['uid'], $sysLanguageUid, $prefix);
                }
            }
        }

        return $this->tree;
    }

    /**
     * Wrapper function for the current TSFE object.
     *
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
