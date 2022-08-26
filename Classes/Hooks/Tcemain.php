<?php
namespace WebVision\WvT3unity\Hooks;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2021 web-vision GmbH
 */
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Core\Database\Connection;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use \TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;

/**
 * This class makes sure that on save of a page the path for the page will be
 * generated and saved for page and all subpages.
 *
 * Also prevents SQL errors for $fields when trying to insert null or empty
 * values as TEXT fields do not allow default values.
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
    const COLUMN_EXCLUDE_SLUG_FOR_SUBPAGES = 'exclude_slug_for_subpages';
    const COLUMN_SLUG = 'slug';
    const TABLE_PAGES = 'pages';
    const TABLE_PAGES_LANGUAGE_OVERLAY = 'pages_language_overlay';

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $db;

    /**
     * Fieldnames that trigger the handler.
     *
     * @var array
     */
    protected $fields = [
        'unity_path',
        'canonical_url',
    ];

    /**
     * Actions that are allowed to trigger the handler.
     *
     * @var array
     */
    protected $actions = [
        'new',
        'update'
    ];

    /**
     * Tables to work on. Only those tables will be processed.
     *
     * @var string
     */
    protected $tablesToProcess = [
        'pages',
        'pages_language_overlay'
    ];

    /**
     * Fields that should not be null on 'new' action.
     *
     * @var array
     */
    protected $preventFieldsFromNull = [
        'unity_path',
        'canonical_url',
    ];

    protected PageRepository $pageRepository;

    public function injectPageRepository(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    // public function __construct(PageRepository $pageRepository)
    // {
    //     $this->pageRepository = $pageRepository;
    // }
    /**
     * Hook to set an empty string for fields that use text as data type and
     * are not required to prevent a SQL warning / error.
     * This occurs when using MySQL in strict mode.
     * It is required as some fields in the unity are filled later on and are
     * not definable in TCA as "empty".
     *
     * @param string $action The action to perform, e.g. 'new'.
     * @param string $table The table affected by action, e.g. 'pages'.
     * @param int $uid The uid of the record affected by action.
     * @param array $modifiedFields The modified fields of the record.
     *
     * @return void
     */
    public function processDatamap_postProcessFieldArray(// @codingStandardsIgnoreLine
        $action,
        $table,
        $uid,
        array &$modifiedFields
    ) {
        if (! $this->checkProcessing($table, $action, $modifiedFields)) {
            return;
        }

        foreach ($this->fields as $field) {
            if (! isset($modifiedFields[$field]) || $modifiedFields[$field] === null) {
                $modifiedFields[$field] = '';
            }
        }
    }

    /**
     * Check whether to continue with the handler or not.
     *
     * @param string $table
     * @param string $action
     * @param array $modifiedFields
     *
     * @return bool
     */
    protected function checkProcessing($table, $action, array $modifiedFields)
    {
        // Do not process if foreign table, unintended action,
        // or fields were changed explicitly.
        if (!(in_array($table, $this->tablesToProcess)) || !(in_array($action, $this->actions))) {
            return false;
        }

        // Process if at least one field of $preventFieldsFromNull
        // is not set or is null on 'new' action.
        if ($action == 'new') {
            foreach ($this->preventFieldsFromNull as $field) {
                if (! isset($modifiedFields[$field]) || $modifiedFields[$field] === null) {
                    return true;
                }
            }
        }

        // Only process if one of the fields was updated or containing new information.
        foreach (array_keys($modifiedFields) as $modifiedFieldName) {
            if (in_array($modifiedFieldName, $this->fields)) {
                return true;
            }
        }

        return false;
    }

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
        if (array_key_exists(static::COLUMN_DOKTYPE, $dataHandler->checkValue_currentRecord) && $dataHandler->checkValue_currentRecord[static::COLUMN_DOKTYPE] >= 199) {
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

        if (!array_key_exists('slug', $databaseData)) {
            return;
        }

        if ($databaseData['slug']) {
            $newRealUrlPath = $databaseData['slug'];
        } else {
            $newRealUrlPath = $dataHandler->checkValue_currentRecord[static::COLUMN_SLUG];
        }

        $this->updateRecord($recordId, $sysLanguageUid, $unityPath, $newRealUrlPath);

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
        $pages = (GeneralUtility::makeInstance(RootlineUtility::class, $uid))->get();
        $data = [];

        if ($sysLanguageUid > 0) {
            $data = $this->getPagesOverlayWithoutFERestriction($pages, $sysLanguageUid);
        } else {
            $data = $pages;
        }

        ksort($data);


        foreach ($data as $record) {
            if ($record[static::COLUMN_IS_SITEROOT] == '1') {
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
    protected function updateRecord($uid, $sysLanguageUid, $unityPath, $newRealUrlPath = null, $newRealUrlPath = null)
    {
        if ($unityPath == '/') {
            $unityPath = '';
        }

        if ($newRealUrlPath != null) {
    
        if ($newRealUrlPath != null) {
            $realUrlPathForPageData = rtrim('/' . ltrim($newRealUrlPath, '/'), '/');
            $unityPath = $realUrlPathForPageData . '.html';
        } else {
            $realUrlPathForPageDataForPageData = rtrim('/' . ltrim($newRealUrlPath, '/'), '/');
            $unityPath = $realUrlPathForPageData . '.html';
        } else {
            $realUrlPathForPageData = rtrim(preg_replace(static::HTML_REGEX, '', $unityPath), '/');
        }
        }

        // set default values for update query
        $tableName = static::TABLE_PAGES;
        $where = [
            static::COLUMN_UID => (int)$uid,
        ];
        $fields = [
            static::COLUMN_UNITY_PATH => $unityPath,
            static::COLUMN_SLUG => $realUrlPathForPageData,
        ];

        // overwrite some settings
        if ($sysLanguageUid > 0) {
            $tableName = static::TABLE_PAGES_LANGUAGE_OVERLAY;
            array_push(
                $where,
                static::COLUMN_SYS_LANGUAGE_UID . '=>' . (int)$sysLanguageUid
            );
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($tableName);
        $connection->update(
            $tableName,
            $fields,
            $where,
            [Connection::PARAM_INT]
        );
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
            if (!$data[static::COLUMN_EXCLUDE_SLUG_FOR_SUBPAGES]) {
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

        $resultSet = GeneralUtility::makeInstance(ConnectionPool::class)
        ->getConnectionForTable(static::TABLE_PAGES)
        ->select(
            ['uid','doktype','title','nav_title','unity_path','slug'],
            static::TABLE_PAGES,
            [
               static::COLUMN_PID => $pid,
               'deleted' => 0,
           ]
        )->fetchAll();

        foreach ($resultSet as $key => $row) {
            $uid = $row[static::COLUMN_UID];
            $row[static::COLUMN_SYS_LANGUAGE_UID] = 0;
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

    /**
     * This method is almost the same as PageRepository::getPagesOverlay
     * but does not set the frontend editing restriction as in the
     * backend are no user groups and it throws an exception.
     *
     * @param array $pagesInput The pages input.
     * @param int $lUid The language uid.
     *
     * @return array
     */
    protected function getPagesOverlayWithoutFERestriction(array $pagesInput, $lUid)
    {
        $page_ids = [];

        foreach ($pagesInput as $origPage) {
            if (is_array($origPage)) {
                // Was the whole record
                $page_ids[] = $origPage['uid'];
            } else {
                // Was the id
                $page_ids[] = $origPage;
            }
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages_language_overlay');

        $result = $queryBuilder->select('*')
            ->from('pages_language_overlay')
            ->where(
                $queryBuilder->expr()->in(
                    'pid',
                    $queryBuilder->createNamedParameter($page_ids, Connection::PARAM_INT_ARRAY)
                ),
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter($lUid, \PDO::PARAM_INT)
                )
            )
            ->execute();

        $overlays = [];
        while ($row = $result->fetch()) {
            $this->pageRepository->versionOL('pages_language_overlay', $row);
            if (is_array($row)) {
                $row['_PAGES_OVERLAY'] = true;
                $row['_PAGES_OVERLAY_UID'] = $row['uid'];
                $row['_PAGES_OVERLAY_LANGUAGE'] = $lUid;
                $origUid = $row['pid'];
                // Unset vital fields that are NOT allowed to be overlaid:
                unset($row['uid']);
                unset($row['pid']);
                $overlays[$origUid] = $row;
            }
        }

        // Create output:
        $pagesOutput = [];
        foreach ($pagesInput as $key => $origPage) {
            if (is_array($origPage)) {
                $pagesOutput[$key] = $origPage;
                if (isset($overlays[$origPage['uid']])) {
                    // Overwrite the original field with the overlay
                    foreach ($overlays[$origPage['uid']] as $fieldName => $fieldValue) {
                        if ($fieldName !== 'uid' && $fieldName !== 'pid') {
                            $pagesOutput[$key][$fieldName] = $fieldValue;
                        }
                    }
                }
            } else {
                if (isset($overlays[$origPage])) {
                    $pagesOutput[$key] = $overlays[$origPage];
                }
            }
        }
        return $pagesOutput;
    }

    /**
     * This method is almost the same as PageRepository::getPagesOverlay
     * but does not set the frontend editing restriction as in the
     * backend are no user groups and it throws an exception.
     *
     * @param array $pagesInput The pages input.
     * @param int $lUid The language uid.
     *
     * @return array
     */
    protected function getPagesOverlayWithoutFERestriction(array $pagesInput, $lUid)
    {
        $page_ids = [];

        foreach ($pagesInput as $origPage) {
            if (is_array($origPage)) {
                // Was the whole record
                $page_ids[] = $origPage['uid'];
            } else {
                // Was the id
                $page_ids[] = $origPage;
            }
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages_language_overlay');

        $result = $queryBuilder->select('*')
            ->from('pages_language_overlay')
            ->where(
                $queryBuilder->expr()->in(
                    'pid',
                    $queryBuilder->createNamedParameter($page_ids, Connection::PARAM_INT_ARRAY)
                ),
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter($lUid, \PDO::PARAM_INT)
                )
            )
            ->execute();

        $overlays = [];
        while ($row = $result->fetch()) {
            $this->pageRepository->versionOL('pages_language_overlay', $row);
            if (is_array($row)) {
                $row['_PAGES_OVERLAY'] = true;
                $row['_PAGES_OVERLAY_UID'] = $row['uid'];
                $row['_PAGES_OVERLAY_LANGUAGE'] = $lUid;
                $origUid = $row['pid'];
                // Unset vital fields that are NOT allowed to be overlaid:
                unset($row['uid']);
                unset($row['pid']);
                $overlays[$origUid] = $row;
            }
        }

        // Create output:
        $pagesOutput = [];
        foreach ($pagesInput as $key => $origPage) {
            if (is_array($origPage)) {
                $pagesOutput[$key] = $origPage;
                if (isset($overlays[$origPage['uid']])) {
                    // Overwrite the original field with the overlay
                    foreach ($overlays[$origPage['uid']] as $fieldName => $fieldValue) {
                        if ($fieldName !== 'uid' && $fieldName !== 'pid') {
                            $pagesOutput[$key][$fieldName] = $fieldValue;
                        }
                    }
                }
            } else {
                if (isset($overlays[$origPage])) {
                    $pagesOutput[$key] = $overlays[$origPage];
                }
            }
        }
        return $pagesOutput;
    }
}
