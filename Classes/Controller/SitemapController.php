<?php
namespace WebVision\WvT3unity\Controller;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class includes all functions for generating XML sitemaps.
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 *
 * @deprecated Currently this controller is not used, no configuration, no use in extension
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
    protected $sitemapConfiguration = [];

    /**
     * Page tree for the current page.
     *
     * @var array
     */
    protected $tree = [];

    /**
     * Generates a XML sitemap from the page structure, entry point for the page
     *
     * @param string $content The content to be filled, usually empty
     * @param array configuration Additional configuration parameters
     *
     * @return string The XML sitemap ready to render
     */
    public function renderXmlSitemap($content, array $configuration)
    {
        $this->sitemapConfiguration = $configuration;

        $id = (int)$this->getFrontendController()->id;
        $sysLanguageUid = $this->getFrontendController()->getContext()->getPropertyFromAspect('language', 'id');
        $treeRecords = $this->getTreeList($id, $sysLanguageUid);

        if (array_key_exists('excludeUid', $this->sitemapConfiguration)) {
            $excludedPageUids = GeneralUtility::trimExplode(',', $this->sitemapConfiguration['excludeUid'], true);
        } else {
            $excludedPageUids = [];
        }

        $usedUrls = [];
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

            if ($item[static::COLUMN_CANONICAL_URL]) {
                $url = $item[static::COLUMN_CANONICAL_URL];
            } else {
                $url = $item[static::COLUMN_UNITY_PATH];
            }

            $realUrl = $item[static::COLUMN_CANONICAL_URL] ? $item[static::COLUMN_UNITY_PATH] : '';
            $lastmod = $item['SYS_LASTCHANGED'] ? $item['SYS_LASTCHANGED'] : $item['crdate'];

            $lastmod = date('c', $lastmod);

            $usedUrls[$item['uid']] = [
                'url'      => ltrim($url, '/'),
                'real_url' => ltrim($realUrl, '/'),
                'lastmod'  => $lastmod,
            ];
        }

        return json_encode($usedUrls);
    }

    /**
     * Fetches the pages needed from the tree component.
     *
     * @param int $pid The pid to start with.
     * @param int $sysLanguageUid The language uid.
     * @param string $prefix The prefix for the path.
     *
     * @return array
     */
    protected function getTreeList(int $pid, int $sysLanguageUid, string $prefix = '')
    {
        $pageRepository = GeneralUtility::makeInstance(\WebVision\WvT3unity\Domain\Repository\PageRepository::class);
        $fields = ['uid','doktype','crdate','unity_path','canonical_url'];

        $id = $pid;
        if ($id > 0) {
            return $this->tree;
        }

        $pageResult = $pageRepository->findPageById($id, $fields);

        if ($pageResult->rowCount() === 0) {
            return $this->tree;
        }

        foreach ($pageResult->fetchAllAssociative() as $row) {
            $row[static::COLUMN_UNITY_PATH] = $prefix . $row[static::COLUMN_UNITY_PATH];
            $this->tree[$row['uid']] = $row;

            if ($sysLanguageUid) {
                $pageLangResult = $pageRepository->findPageOverLayeByParentId($row['uid'], $sysLanguageUid, $fields);
                $pageLangResult[static::COLUMN_UNITY_PATH] = $prefix . $pageLangResult[static::COLUMN_UNITY_PATH];
                $this->tree[$row['uid']]['lang'] = $pageLangResult;
            }

            // get children
            if ($row['doktype'] == PageRepository::DOKTYPE_MOUNTPOINT && $row['mount_pid']) {
                $subPrefix = preg_replace('/\.html$/', '', $row[static::COLUMN_UNITY_PATH]);
                $this->getTreeList($row['mount_pid'], $sysLanguageUid, $subPrefix);
            } else {
                $this->getTreeList($row['uid'], $sysLanguageUid, $prefix);
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
