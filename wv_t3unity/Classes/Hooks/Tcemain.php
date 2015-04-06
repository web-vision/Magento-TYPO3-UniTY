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

	        $recordPath = self::getRecordPath($recordId,'',1000);
	        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages', 'uid =' . $recordId, array('unity_path' => $recordPath) );

	        $treeRecords = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine($recordId);

            foreach ($treeRecords as $row) {
				if ($row['uid'] > 0) {
  	                $recordPath = self::getRecordPath($row['uid'],'',1000);
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages', 'uid =' . $row['uid'], array('unity_path' => $this->buildPath($recordPath)) );
				}
            }

	        $childRecords = self::getPageChilds($recordId);
	        foreach ($childRecords as $row) {
		        if ($row['uid'] > 0) {
			        $recordPath = self::getRecordPath($row['uid'],'',1000);
			        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages', 'uid =' . $row['uid'], array('unity_path' => $this->buildPath($recordPath)) );
		        }
	        }

        }
        if ($tableName == 'pages_language_overlay') {
	        $titleLimit = 1000;
	        $output = ($fullOutput = '/');

	        $record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages_language_overlay',$recordId);
	        /** @var \TYPO3\CMS\Frontend\Page\PageRepository $pageRepository */
	        $pageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
	        $pageRepository->sys_language_uid = $record['sys_language_uid'];

	        $rootline = new \TYPO3\CMS\Core\Utility\RootlineUtility($record['pid'],'',$pageRepository);
	        foreach($rootline->get() as $row) {
		        if ($row['uid'] === 0) {
			        continue;
		        }
		        if (array_key_exists('is_siteroot',$row)) {
			        if ($row['is_siteroot'] == 0) {
				        print_r($row);

				        if ($row['nav_title'] !== '') {
					        $output = '/' . strip_tags($row['nav_title']) . $output;
				        } else {
					        $output = '/' . strip_tags($row['title']) . $output;
				        }
			        }
		        }
	        }
	        $recordPath = self::buildPath($output);

	        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages_language_overlay', 'uid =' . $recordId, array('unity_path' => $this->buildPath($recordPath)) );
        }
    }

	/**
	 * buildPath
	 *
	 * @param $recordPath
	 *
	 * @return string
	 */
	protected function buildPath($recordPath) {
		$pathLong = str_replace(' ','-',strtolower($recordPath));
		$path = substr($pathLong, 0, -1);
		if ($path ==  '') {
			$path = 'index';
		}
		return $path . '.html';
	}

	/**
	 * getRecordPath
	 *
	 * @param     $uid
	 * @param     $clause
	 * @param     $titleLimit
	 * @param int $fullTitleLimit
	 *
	 * @return array|string
	 */
	static public function getRecordPath($uid, $clause, $titleLimit, $fullTitleLimit = 0) {
		if (!$titleLimit) {
			$titleLimit = 1000;
		}
		$loopCheck = 100;
		$output = ($fullOutput = '/');
		$clause = trim($clause);
		if ($clause !== '' && substr($clause, 0, 3) !== 'AND') {
			$clause = 'AND ' . $clause;
		}
		$data = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine($uid, $clause);
		foreach ($data as $record) {
			if ($record['uid'] === 0) {
				continue;
			}
			if (array_key_exists('is_siteroot',$record)) {
				if ($record['is_siteroot'] == 0) {
					if ($record['nav_title'] !== '') {
						$output = '/' . \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(strip_tags($record['nav_title']), $titleLimit) . $output;
					} else {
						$output = '/' . \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(strip_tags($record['title']), $titleLimit) . $output;
					}
					if ($fullTitleLimit) {
						if ($record['nav_title'] !== '') {
							$output = '/' . \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(strip_tags($record['nav_title']), $titleLimit) . $fullOutput;
						} else {
							$output = '/' . \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(strip_tags($record['title']), $titleLimit) . $fullOutput;
						}
					}
				}
			} else {
				if ($record['nav_title'] !== '') {
					$output = '/' . \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(strip_tags($record['nav_title']), $titleLimit) . $output;
				} else {
					$output = '/' . \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(strip_tags($record['title']), $titleLimit) . $output;
				}
				if ($fullTitleLimit) {
					if ($record['nav_title'] !== '') {
						$output = '/' . \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(strip_tags($record['nav_title']), $titleLimit) . $fullOutput;
					} else {
						$output = '/' . \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs(strip_tags($record['title']), $titleLimit) . $fullOutput;
					}
				}
			}
		}
		if ($fullTitleLimit) {
			return array($output, $fullOutput);
		} else {
			return $output;
		}
	}

	/**
	 * getPageChilds
	 *
	 * @param        $uid
	 * @param string $clause
	 *
	 * @return array
	 */
	static protected function getPageChilds($uid, $clause = '') {
		static $getPageChilds_cache = array();
		$ident = $uid . '-' . $clause;
		if (is_array($getPageChilds_cache[$ident])) {
			$row = $getPageChilds_cache[$ident];
		} else {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid,uid,title,TSconfig,is_siteroot,storage_pid,t3ver_oid,t3ver_wsid,t3ver_state,t3ver_stage,backend_layout_next_level', 'pages', 'pid=' . (int)$uid . ' ' . \TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause('pages') . ' ' . $clause);
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$result[] = $row;
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($res);
		}
		return $result;
	}
}