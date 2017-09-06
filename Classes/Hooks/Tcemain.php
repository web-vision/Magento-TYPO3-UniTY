<?php
namespace WebVision\WvT3unity\Hooks;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Backend\Utility\BackendUtility;
use \TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * This class will make sure that on save of a page the path for the page will be
 * generated and saved for the saved page and all subpages.
 *
 * @author Tim Werdin <t.werdin@web-vision.de>
 */
class Tcemain
{
    const HTML_REGEX = '/\.html$/';
    const KEY_CHILDREN = 'children';
    const COLUMN_UID = 'uid';
    const COLUMN_PID = 'pid';
    const COLUMN_TITLE = 'title';
    const COLUMN_DOKTYPE = 'doktype';
    const COLUMN_NAV_TITLE = 'nav_title';
    const COLUMN_UNITY_PATH = 'unity_path';
    const COLUMN_IS_SITEROOT = 'is_siteroot';
    const COLUMN_SYS_LANGUAGE_UID = 'sys_language_uid';
    const COLUMN_TX_REALURL_EXCLUDE = 'tx_realurl_exclude';
    const COLUMN_TX_REALURL_PATHSEGMENT = 'tx_realurl_pathsegment';
    const COLUMN_TX_REALURL_PATHOVERRIDE = 'tx_realurl_pathoverride';
    const TABLE_PAGES = 'pages';
    const TABLE_PAGES_LANGUAGE_OVERLAY = 'pages_language_overlay';

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $db;

    /**
     * A TCEmain hook to expire old records and add new ones
     *
     * @param string $status
     * @param string $tableName
     * @param int $recordId
     * @param array $databaseData
     * @param object $dataHandler
     *
     * @return void
     */
    public function processDatamap_afterDatabaseOperations(
        $status,
        $tableName,
        $recordId,
        array $databaseData,
        $dataHandler
    ) {
        // new entry via drag & drop don't process
        if ($recordId === 'NEW12345') {
            return;
        }

        // new normal entry has hashed record id, get real id from data handler
        if ($status == 'new' && strpos($recordId, 'NEW') === 0) {
            $recordId = $dataHandler->substNEWwithIDs[$recordId];
        }

        // don't generate path for folder, recycler and menu separator
        if ($dataHandler->checkValue_currentRecord[static::COLUMN_DOKTYPE] >= 199) {
            return;
        }

        switch ($tableName) {
            case static::TABLE_PAGES:
                $pagesUid = $recordId;
                $sysLanguageUid = 0;
                break;
            case static::TABLE_PAGES_LANGUAGE_OVERLAY:
                $pagesUid = $dataHandler->checkValue_currentRecord[static::COLUMN_PID];
                $sysLanguageUid = $dataHandler->checkValue_currentRecord[static::COLUMN_SYS_LANGUAGE_UID];
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
     * @return string
     */
    protected function getRecordPath($uid, $sysLanguageUid)
    {
        $output = '';
        $pageRepo = GeneralUtility::makeInstance(PageRepository::class);
        $pageRepo->sys_language_uid = $sysLanguageUid;
        $data = $pageRepo->getRootLine($uid);
        ksort($data);
        foreach ($data as $record) {
            if ($record[static::COLUMN_IS_SITEROOT] == '1' || $record[static::COLUMN_TX_REALURL_EXCLUDE]) {
                continue;
            }

            $output = $this->addToPath($output, $record);
        }

        if (strlen($output) == 0 && isset($record) && $record[static::COLUMN_IS_SITEROOT] == '1') {
            $output = '/index.html';
        }

        return $output;
    }

    /**
     * This method will update the record with the given uid and sysLanguageUid with
     * the given unityPath.
     *
     * @param int $uid The uid of the page to update.
     * @param int $sysLanguageUid The language uid of the page to update.
     * @param string $unityPath The unity path to set.
     *
     * @return void
     */
    protected function updateRecord($uid, $sysLanguageUid, $unityPath)
    {
        if ($unityPath == '/') {
            $unityPath = '';
        }
        $realUrlPath = rtrim(preg_replace(static::HTML_REGEX, '', $unityPath), '/');

        // set default values for update query
        $tableName = static::TABLE_PAGES;
        $where = static::COLUMN_UID . ' = ' . (int)$uid;
        $fields = [
            static::COLUMN_UNITY_PATH             => $unityPath,
            static::COLUMN_TX_REALURL_PATHSEGMENT => $realUrlPath,
        ];

        // overwrite some settings
        if ($sysLanguageUid > 0) {
            $tableName = static::TABLE_PAGES_LANGUAGE_OVERLAY;
            $where .= ' AND ' . static::COLUMN_SYS_LANGUAGE_UID . ' = ' . (int)$sysLanguageUid;
        } elseif ($realUrlPath) {
            $fields[static::COLUMN_TX_REALURL_PATHOVERRIDE] = 1;
        }

        $this->db->exec_UPDATEquery($tableName, $where, $fields);
    }

    /**
     * This method will find all subpages of the page with the given uid and will
     * update these pages if needed.
     *
     * @param int $uid The uid to find the children for.
     * @param int $sysLanguageUid The language uid for child tree.
     * @param string $path The current path.
     *
     * @return void
     */
    protected function updateSubPages($uid, $sysLanguageUid, $path)
    {
        // remove previously added .html
        $path = preg_replace(static::HTML_REGEX, '', $path) . '/';

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
     * This method will check if the sub page given in $data needs an update and if
     * so it will update the record.
     * If the sub page has children this method will recursively call itself for
     * each child.
     *
     * @param array $data The subpage to update.
     * @param string $currentPath The current path.
     *
     * @return void
     */
    protected function updateSubPage(array $data, $currentPath)
    {
        // if unity path doesn't start with the current path it needs an update
        if (strpos($data[static::COLUMN_UNITY_PATH], $currentPath) !== 0 || $currentPath == '/') {
            $unityPath = $currentPath;
            if (!$data[static::COLUMN_TX_REALURL_EXCLUDE]) {
                $unityPath = $this->addToPath($currentPath, $data);
            }

            if (array_key_exists(static::COLUMN_UID, $data) && $data[static::COLUMN_DOKTYPE] < 199) {
                $this->updateRecord($data[static::COLUMN_UID], $data[static::COLUMN_SYS_LANGUAGE_UID], $unityPath);
            }

            if (array_key_exists(static::KEY_CHILDREN, $data)) {
                foreach ($data[static::KEY_CHILDREN] as $subPage) {
                    $this->updateSubPage($subPage, $unityPath);
                }
            }
        }
    }

    /**
     * This method builds a recursive array representing the page tree beginning with
     * the given pid.
     * If $sysLanguageUid is given and greater 0 the translation will be used as
     * well.
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
        $treeList = [];
        if (!$pid) {
            return $treeList;
        }

        $resultSet = $this->db->exec_SELECTquery(
            'uid, doktype, title, nav_title, unity_path, tx_realurl_exclude',
            static::TABLE_PAGES,
            static::COLUMN_PID . ' = ' . $pid . ' ' . BackendUtility::deleteClause(static::TABLE_PAGES)
        );

        while (($row = $this->db->sql_fetch_assoc($resultSet))) {
            $uid = $row[static::COLUMN_UID];

            $row[static::COLUMN_SYS_LANGUAGE_UID] = 0;

            if ($sysLanguageUid) {
                // get localized data
                $langResultSet = $this->db->exec_SELECTquery(
                    'uid, doktype, title, nav_title, unity_path',
                    static::TABLE_PAGES_LANGUAGE_OVERLAY,
                    static::COLUMN_PID . ' = ' . $uid . ' ' . BackendUtility::deleteClause(
                        static::TABLE_PAGES_LANGUAGE_OVERLAY
                    ) . ' AND ' . static::COLUMN_SYS_LANGUAGE_UID . ' = ' . $sysLanguageUid
                );
                $langResult = $this->db->sql_fetch_assoc($langResultSet);
                if ($langResult) {
                    $row = array_merge($row, $langResult);
                    $row[static::COLUMN_SYS_LANGUAGE_UID] = $sysLanguageUid;
                } else {
                    unset($row[static::COLUMN_UID]);
                }
            }

            $treeList[$uid] = $row;

            // get children
            $children = $this->getTreeList($uid, $sysLanguageUid);
            if (!empty($children)) {
                $treeList[$uid][static::KEY_CHILDREN] = $children;
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
        $path = preg_replace(static::HTML_REGEX, '', $path);
        // add slash and remove possibly trailing slashes before
        $path = rtrim($path, '/') . '/';

        // if array is given use nav_title or title or first element if neiter is given
        if (is_array($newElement)) {
            if (array_key_exists(static::COLUMN_NAV_TITLE, $newElement) && $newElement[static::COLUMN_NAV_TITLE]) {
                $newElement = $newElement[static::COLUMN_NAV_TITLE];
            } elseif (array_key_exists(static::COLUMN_TITLE, $newElement)) {
                $newElement = $newElement[static::COLUMN_TITLE];
            } else {
                $newElement = reset($newElement);
            }
        }

        // remove html tags and make string lower case
        $newElement = mb_convert_case(strip_tags($newElement), MB_CASE_LOWER, 'utf-8');

        $chars = [
            'ä' => 'ae',
            'ö' => 'oe',
            'ü' => 'ue',
            'ß' => 'ss',
            ' ' => '-',
        ];

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
