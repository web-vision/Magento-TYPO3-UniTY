<?php
namespace WebVision\WvT3unity\Hooks;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Tim Werdin  <t.werdin@web-vision.de>, web-vision GmbH
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
 * This class will make sure that on save of a page the path for the page will be generated and saved for the saved page
 * and all subpages.
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 */
class Tcemain
{
    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $db;

    /**
     * A TCEmain hook to expire old records and add new ones
     *
     * @param string                                   $status
     * @param string                                   $tableName
     * @param int                                      $recordId
     * @param array                                    $databaseData
     * @param object $dataHandler
     *
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

        // don't generate path for folder, recycler and menu separator
        if ($dataHandler->checkValue_currentRecord['doktype'] >= 199) {
            return;
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

        $this->db = $GLOBALS['TYPO3_DB'];

        $unityPath = $this->getRecordPath($pagesUid, $sysLanguageUid);

        $this->updateRecord($recordId, $sysLanguageUid, $unityPath);

        $this->updateSubPages($pagesUid, $sysLanguageUid, $unityPath);
    }

    /**
     * This method returns the path for the given uid and language uid.
     *
     * @param int $uid The uid of the page to generate the path for.
     * @param int $sysLanguageUid The language uid for path generation.
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
            if ($record['is_siteroot'] == '1' || $record['tx_realurl_exclude']) {
                continue;
            }

            $output = $this->addToPath($output, $record);
        }

        if (strlen($output) == 0 && isset($record) && $record['is_siteroot'] == '1') {
            $output = '/index.html';
        }

        return $output;
    }

    /**
     * This method will update the record with the given uid and sysLanguageUid with the given unityPath.
     *
     * @param int $uid The uid of the page to update.
     * @param int $sysLanguageUid The language uid of the page to update.
     * @param string $unityPath The unity path to set.
     */
    protected function updateRecord($uid, $sysLanguageUid, $unityPath)
    {
        if ($unityPath == '/') {
            $unityPath = '';
        }
        $realUrlPath = rtrim(preg_replace('/\.html$/', '', $unityPath), '/');

        // set default values for update query
        $tableName = 'pages';
        $where = 'uid = ' . (int)$uid;
        $fields = array(
            'unity_path'             => $unityPath,
            'tx_realurl_pathsegment' => $realUrlPath,
        );

        // overwrite some settings
        if ($sysLanguageUid > 0) {
            $tableName .= '_language_overlay';
            $where .= ' AND sys_language_uid = ' . (int)$sysLanguageUid;
        } elseif ($realUrlPath) {
            $fields['tx_realurl_pathoverride'] = 1;
        }

        $this->db->exec_UPDATEquery($tableName, $where, $fields);
    }

    /**
     * This method will find all subpages of the page with the given uid and will update these pages if needed.
     *
     * @param int $uid The uid to find the children for.
     * @param int $sysLanguageUid The language uid for the generation of the child tree.
     * @param string $path The current path.
     */
    protected function updateSubPages($uid, $sysLanguageUid, $path)
    {
        // remove previously added .html
        $path = preg_replace('/\.html$/', '', $path) . '/';

        // remove index as a special case for the root page
        if ($path == '/index/' || $path == '') {
            $path = '/';
        }

        $subPages = $this->getTreeList($uid, $sysLanguageUid);

        foreach ($subPages as $subPage) {
            $this->updateSubPage($subPage, $path);
        }
    }

    /**
     * This method will check if the sub page given in $data needs an update and if so it will update the record.
     * If the sub page has children this method will recursively call itself for each child.
     *
     * @param array $data The subpage to update.
     * @param string $currentPath The current path.
     */
    protected function updateSubPage($data, $currentPath)
    {
        // if unity path doesn't start with the current path it needs an update
        if (strpos($data['unity_path'], $currentPath) !== 0 || $currentPath == '/') {
            $unityPath = $currentPath;
            if (!$data['tx_realurl_exclude']) {
                $unityPath = $this->addToPath($currentPath, $data);
            }

            if (array_key_exists('uid', $data) && $data['doktype'] < 199) {
                $this->updateRecord($data['uid'], $data['sys_language_uid'], $unityPath);
            }

            if (array_key_exists('children', $data)) {
                foreach ($data['children'] as $subPage) {
                    $this->updateSubPage($subPage, $unityPath);
                }
            }
        }
    }

    /**
     * This method builds a recursive array representing the page tree beginning with the given pid.
     * If $sysLanguageUid is given and greater 0 the translation will be used as well.
     *
     * @param int $pid The pid to generate the array for.
     * @param int $sysLanguageUid The language uid to add translations.
     *
     * @return array
     */
    protected function getTreeList($pid, $sysLanguageUid)
    {
        $pid = (int)$pid;
        if ($pid < 0) {
            $pid = abs($pid);
        }
        $treeList = array();
        if ($pid) {
            $resultSet = $this->db->exec_SELECTquery(
                'uid, doktype, title, nav_title, unity_path, tx_realurl_exclude',
                'pages',
                'pid=' . $pid . ' ' . BackendUtility::deleteClause('pages')
            );
            while (($row = $this->db->sql_fetch_assoc($resultSet))) {
                $uid = $row['uid'];

                $row['sys_language_uid'] = 0;

                if ($sysLanguageUid) {
                    // get localized data
                    $langResultSet = $this->db->exec_SELECTquery(
                        'uid, doktype, title, nav_title, unity_path',
                        'pages_language_overlay',
                        'pid=' . $uid . ' ' . BackendUtility::deleteClause('pages_language_overlay') . ' AND sys_language_uid = ' . $sysLanguageUid
                    );
                    $langResult = $this->db->sql_fetch_assoc($langResultSet);
                    if ($langResult) {
                        $row = array_merge($row, $langResult);
                        $row['sys_language_uid'] = $sysLanguageUid;
                    } else {
                        unset($row['uid']);
                    }
                }

                $treeList[$uid] = $row;

                // get children
                $children = $this->getTreeList($uid, $sysLanguageUid);
                if (!empty($children)) {
                    $treeList[$uid]['children'] = $children;
                }
            }
        }

        return $treeList;
    }

    /**
     * This method cleans up the $newElement and adds it to the given path.
     *
     * @param string $path The current path.
     * @param string $newElement The new element to add.
     *
     * @return string
     */
    protected function addToPath($path, $newElement)
    {
        // remove previously added .html
        $path = preg_replace('/\.html$/', '', $path);
        // add slash and remove possibly trailing slashes before
        $path = rtrim($path, '/') . '/';

        // if array is given use nav_title or title or first element if neiter is given
        if (is_array($newElement)) {
            if (array_key_exists('nav_title', $newElement) && $newElement['nav_title']) {
                $newElement = $newElement['nav_title'];
            } elseif (array_key_exists('title', $newElement)) {
                $newElement = $newElement['title'];
            } else {
                $newElement = reset($newElement);
            }
        }

        // remove html tags and make string lower case
        $newElement = mb_convert_case(strip_tags($newElement), MB_CASE_LOWER, 'utf-8');

        $chars = array(
            'ä' => 'ae',
            'ö' => 'oe',
            'ü' => 'ue',
            'ß' => 'ss',
            ' ' => '-',
        );

        // replace german characters and spaces
        foreach ($chars as $search => $replace) {
            $newElement = str_replace($search, $replace, $newElement);
        }

        // replace all non ascii characters like è
        $newElement = iconv('UTF-8', 'ASCII//TRANSLIT', $newElement);
        $newElement = iconv('ASCII', 'UTF-8', $newElement);

        // translit can result in upper case characters € -> EUR
        $newElement = strtolower($newElement);

        // replace everything that is not in the alphabet or a number with a minus
        $newElement = preg_replace('/[^a-z0-9-]/', '-', $newElement);

        // remove multiple -
        $newElement = preg_replace('/--{1,}/', '-', $newElement);

        // remove leading and trailing minus
        $newElement = trim($newElement, '-');

        // urlencode to be absolute sure that it is a valid url
        return $path . urlencode($newElement) . '.html';
    }
}
