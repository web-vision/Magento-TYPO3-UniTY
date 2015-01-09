<?php
namespace WebVision\WvT3unity\Hooks;

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

class Tcemain {

    /**
     * A TCEmain hook to expire old records and add new ones
     *
     * @param string $status 'new' (ignoring) or 'update'
     * @param string $tableName
     * @param int $recordId
     * @param array $databaseData
     * @return void
     */
    public function processDatamap_afterDatabaseOperations($status, $tableName, $recordId, array $databaseData) {

        if ($tableName == 'pages') {

            /** @var \WebVision\WvT3unity\Controller\SitemapController $xmlSitemapController */
            $xmlSitemapController = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\WebVision\WvT3unity\Controller\SitemapController');
            $treeRecords = $xmlSitemapController->fetchPagesFromTreeStructure($recordId);

            foreach ($treeRecords as $row) {
                $item = $row['row'];

                $conf = array(
                    'parameter' => $item['uid']
                );

                /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $frontendController */
                $frontendController = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController');

                $url = $frontendController->cObj->typoLink_URL($conf);
                $urlParts = parse_url($url);

                $GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages', 'uid =' . $recordId, array('unity_path' => $urlParts['path']) );

            }

        }
        /*if ($tableName == 'pages_language_overlay') {
            $select = '*';
            $from = 'tx_realurl_pathcache';
            $where = 'page_id = '.$recordId.' AND language_id != 0';
            $group = '';
            $order = '';
            $limit = '';

            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $group, $order, $limit);
            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $path = $row['pagepath'];
                $language = $row['sys_language_uid'];

                if ($path != '') {
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages_language_overlay', 'pid = '.$recordId.'AND sys_language_uid = '.$language ,array('unity_path' => $path));
                }
            }

        }*/
    }

}