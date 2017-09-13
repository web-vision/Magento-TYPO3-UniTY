<?php

namespace WebVision\WvT3unity\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClearCacheService
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
     * @param $pageUid
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
     *
     */
    public function clearCacheForAllPages()
    {
        $this->sendClearCacheSignalToMagento();
    }


    /**
     * @param null $pagePath
     */
    protected function sendClearCacheSignalToMagento($pagePath = NULL)
    {

        if (empty($this->extConf['MagentoUrl'])) {
            $this->displayFlashMessage('No Magento Instance defined in ExtManager', FlashMessage::ERROR);
            return;
        }

        if (!empty($pagePath)) {
            // @todo: until single cache invalidation is implemented, we kill the whole cache instead
            GeneralUtility::getUrl($this->extConf['MagentoUrl']. '/rest/V1/unity/clearAllCaches');
            $this->displayFlashMessage('Magento Cache invalidated for Page: ' . $pagePath);
        } else {
            GeneralUtility::getUrl($this->extConf['MagentoUrl']. '/rest/V1/unity/clearAllCaches');
            $this->displayFlashMessage('Whole Magento Cache was invalidated.');
        }

        return;
    }

    /**
     * @param $text
     * @param int $severity
     */
    protected function displayFlashMessage($text, $severity = FlashMessage::OK)
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class,
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
