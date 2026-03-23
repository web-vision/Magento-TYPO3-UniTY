<?php

declare(strict_types=1);

namespace WebVision\WvT3unity\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class PageRepository
{
    /**
     * @param string[] $field
     */
    public function findPageById(int $id, array $field): Result
    {
        $fields = array_merge($field, ['mount_pid', 'nav_hide', 'SYS_LASTCHANGED']);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        return $queryBuilder->select(...$fields)
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('pid', $id)
            )->executeQuery();
    }

    /**
     * @param string[] $fields
     */
    public function findPageOverLayeByParentId(int|string $uid, int $sysLanguageUid, array $fields = []): Result
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        return $queryBuilder->select(...array_values($fields))
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('pid', (int)$uid),
                $queryBuilder->expr()->eq('sys_language_uid', $sysLanguageUid)
            )->executeQuery();
    }
}
