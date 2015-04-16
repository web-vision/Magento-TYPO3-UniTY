<?php
namespace WebVision\WvT3unity\Hooks;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Backend\Utility\BackendUtility;

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
class Tcemain
{
    /**
     * A TCEmain hook to expire old records and add new ones
     *
     * @param string $status 'new' (ignoring) or 'update'
     * @param string $tableName
     * @param int $recordId
     * @param array $databaseData
     * @return void
     */
    public function processDatamap_afterDatabaseOperations($status, $tableName, $recordId, array $databaseData, $dataHandler)
    {
        // new entry via drag & drop don't process
        if ($recordId === 'NEW12345') {
            return;
        }

        // new normal entry has hashed record id, get real id from data handler
        if ($status == 'new' && strpos($recordId, 'NEW') === 0) {
            /** @var \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler */
            $recordId = $dataHandler->substNEWwithIDs[$recordId];
        }

        switch ($tableName) {
            case 'pages':
                $pagesUid = $recordId;
                $sysLanguageUid = 0;
                break;
            case 'pages_language_overlay':
                $pagesUid = $dataHandler->checkValue_currentRecord['pid'];
                $sysLanguageUid = $dataHandler->checkValue_currentRecord['sys_language_uid'];
                break;
            default:
                return;
        }

        /** @var \TYPO3\CMS\Core\Database\DatabaseConnection $db */
        $db = $GLOBALS['TYPO3_DB'];

        $unityPath = $this->getRecordPath($pagesUid, $sysLanguageUid);
        $db->exec_UPDATEquery($tableName, 'uid =' . $recordId, array('unity_path' => $unityPath));

        /*
        // WVTODO this goes up the tree but we need code down the tree
        $treeRecords = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine($recordId);

        foreach ($treeRecords as $row) {

            $recordPath = self::getRecordPath($row['uid'],'',1000);
            $db->exec_UPDATEquery('pages_language_overlay', 'uid =' . $row['uid'], array('unity_path' => $this->buildPath($recordPath)) );
        }
        */
    }

    /**
     * getRecordPath
     *
     * @param int $uid
     * @param int $sysLanguageUid
     *
     * @return array|string
     */
    protected function getRecordPath($uid, $sysLanguageUid)
    {
        $output = '';
        /** @var \TYPO3\CMS\Frontend\Page\PageRepository $pageRepo */
        $pageRepo = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
        $pageRepo->sys_language_uid = $sysLanguageUid;
        $data = $pageRepo->getRootLine($uid);
        ksort($data);
        foreach ($data as $record) {
            if ($record['is_siteroot'] == '1') {
                continue;
            }

            $output .= '/' . $this->cleanTitle($record['nav_title'] ? $record['nav_title'] : $record['title']);
        }

        $output = str_replace(' ', '-', strtolower($output));

        if(strlen($output) == 0) {
            $output = '/index';
        }

        return $output . '.html';
    }

    protected function cleanTitle($title)
    {
        return urlencode(strip_tags($title));
    }
}
