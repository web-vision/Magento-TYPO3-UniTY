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
            $select = '*';
            $from = 'tx_realurl_pathcache';
            $where = 'page_id = '.$recordId;
            $group = '';
            $order = '';
            $limit = '';

            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $group, $order, $limit);
            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $path = $row['pagepath'];

                if ($path != '') {
                    $GLOBALS['TYPO3_DB']->exec_INSERTquery('pages', array('unity_path' => $path));
                }
            }
        }
    }

}