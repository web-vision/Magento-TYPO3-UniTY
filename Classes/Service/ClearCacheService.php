<?php
namespace WebVision\WvT3unity\Service;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClearCacheService
 *
 * @package WebVision\WvT3unity\Service
 */
class ClearCacheService
{
    /**
     * @var array
     */
    protected $extConf = [];

    /**
     * ClearCacheService constructor.
     */
    public function __construct()
    {
        $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wv_t3unity']);
    }

    /**
     * @param int $pageUid
     */
    public function clearCacheForSinglePage($pageUid)
    {
        if ($this->extConf['SupportSingleCacheInvalidation'] == 1) {
            $pagePath = $this->getPagePath($pageUid);
            $this->sendClearCacheSignalToMagento($pagePath);
        } else {
            $this->sendClearCacheSignalToMagento();
        }
    }

    /**
     * @return void
     */
    public function clearCacheForAllPages()
    {
        $this->sendClearCacheSignalToMagento();
    }

    /**
     * @param string $pagePath
     */
    protected function sendClearCacheSignalToMagento($pagePath = NULL)
    {
        if (empty($this->extConf['MagentoUrl'])) {
            $this->displayFlashMessage(
                'No Magento Instance defined in ExtManager', 
                FlashMessage::ERROR
            );
            return;
        }

        if (! empty($pagePath)) {
            $this->displayFlashMessage('Magento Cache invalidated for Page: ' . $pagePath);
            // @todo implement Magento Cache for one page
        } else {
            $this->displayFlashMessage('Whole Magento Cache was invalidated');
            // @todo implement invalidation of whole cache
        }

        return;
    }

    /**
     * @param $text
     * @param int $severity
     */
    protected function displayFlashMessage($text, $severity = FlashMessage::OK)
    {
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $text,
            'Clear Cache',
            $severity,
            true
        );

        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }

    /**
     * Gets the pagePath for magento
     * to clear cache for specific page
     *
     * @param $pageUid
     *
     * @return string
     */
    protected function getPagePath($pageUid)
    {

        $queryBuilder = $this->getQueryBuilder('pages');
        $result = $queryBuilder->select('unity_path')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('uid', (int)$pageUid)
            )->execute()
            ->fetchAll();

        return $result[0]['unity_path'];
    }

    /**
     * @param $table
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getQueryBuilder($table)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        return $queryBuilder;
    }
}
