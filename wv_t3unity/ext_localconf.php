<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['FE']['pageOverlayFields'] .= ',unity_path,path_canonical_url';

$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wv_t3unity']);

if ($extensionConfiguration['xmlSitemap'] == '1') {
    if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {
        $realurl = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'];
        if (is_array($realurl)) {
            foreach ($realurl as $host => $cnf) {
                if (!is_array($realurl[$host])) {
                    continue;
                }

                if (!isset($realurl[$host]['fileName'])) {
                    $realurl[$host]['fileName'] = array();
                }
                $realurl[$host]['fileName']['index']['sitemap.xml']['keyValues']['type'] = 776;
            }
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'] = $realurl;
        }
    }
}

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['tx_wvt3unity'] = 'WebVision\WvT3unity\Hooks\Tcemain';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['tx_wvt3unity'] = 'WebVision\WvT3unity\Hooks\Tcemain';
