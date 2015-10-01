<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$additionalColumns = array(
    'hidden_xs'    => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_xs',
        'config'  => array(
            'type' => 'check',
        ),
    ),
    'hidden_sm'    => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_sm',
        'config'  => array(
            'type' => 'check',
        ),
    ),
    'hidden_md'    => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_md',
        'config'  => array(
            'type' => 'check',
        ),
    ),
    'hidden_lg'    => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_lg',
        'config'  => array(
            'type' => 'check',
        ),
    ),
    'magento_skus' => array(
        'exclude' => 0,
        'label'   => 'LLL:EXT:wv_t3unity/Resources/Private/Language/Backend.xlf:tt_content.magento_skus',
        'config'  => array(
            'type' => 'input',
        ),
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'responsive',
    'hidden_xs;LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_xs, hidden_sm;LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_sm, hidden_md;LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_md, hidden_lg;LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_lg'
);
// WVTODO add palett responsive to all types after frames
#\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;Responsive;responsive', '', 'after:frames');
