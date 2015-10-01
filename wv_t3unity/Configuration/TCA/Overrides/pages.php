<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$additionalColumns = array(
    'unity_path'             => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tx_wvt3unity_domain_model_pages.path',
        'config'  => array(
            'type' => 'input',
            'size' => 70,
            'max'  => 70,
            'eval' => 'trim',
        ),
    ),
    'canonical_url'          => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_db.xlf:tx_wvt3unity_domain_model_pages.canonical_url',
        'config'  => array(
            'type' => 'input',
            'size' => 70,
            'max'  => 70,
            'eval' => 'trim',
        ),
    ),
    'tx_realurl_pathsegment' => array(
        'label'       => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_pathsegment',
        'displayCond' => 'FIELD:tx_realurl_exclude:!=:1',
        'exclude'     => 1,
        'config'      => array(
            'type'     => 'input',
            'max'      => 255,
            'eval'     => 'trim,nospace,lower',
            'readOnly' => 1,
        ),
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $additionalColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'full_path, path, canonical_url',
    1,
    'before:keywords'
);
