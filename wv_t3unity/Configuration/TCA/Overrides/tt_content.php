<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

$additioncalColumns = array(
    'hidden_xs' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_xs',
        'config' => array(
            'type' => 'check',
        )
    ),
    'hidden_sm' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_sm',
        'config' => array(
            'type' => 'check',
        )
    ),
    'hidden_md' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_md',
        'config' => array(
            'type' => 'check',
        ),
    ),
    'hidden_lg' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tt_content.hidden_lg',
        'config' => array(
            'type' => 'check',
        ),
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns,1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'hidden_xs;;;;1-1-1, hidden_sm;;;;1-1-1, hidden_md;;;;1-1-1, hidden_lg;;;;1-1-1');
