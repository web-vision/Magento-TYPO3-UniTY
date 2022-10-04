<?php declare(strict_types=1);

namespace WebVision\WvT3unity\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageRepository
{
    /**
     * @return Result|int
     */
    public function findPageById(int $id, array $field)
    {
        $fields = array_merge($field ?? [], ['mount_pid','nav_hide','SYS_LASTCHANGED']);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $result = $queryBuilder->select(...$fields)
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('pid', $id)
            )->execute();

        return $result;
    }

    /**
     * @return Result|int
     */
    public  function findPageOverLayeByParentId($uid, int $sysLanguageUid, array $fields = [])
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $pageLangResult = $queryBuilder->select(...$fields)
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('pid', (int)$uid),
                $queryBuilder->expr()->eq('sys_language_uid', $sysLanguageUid)
            )->execute();

        return $pageLangResult;
    }
}