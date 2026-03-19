<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Service;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\WorkspaceRestriction;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3\CMS\Core\Domain\RecordFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

readonly class PleskServerRecordService
{
    private const TABLE = 'tx_pleskwidget_server';

    public function __construct(
        private RecordFactory $recordFactory,
        private ConnectionPool $connectionPool,
    ) {}

    /**
     * @return Record[]
     */
    public function getPleskServerRecords(): array
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryResult = $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->executeQuery();

        $pleskServerRecords = [];

        try {
            while ($pleskServer = $queryResult->fetchAssociative()) {
                BackendUtility::workspaceOL(self::TABLE, $pleskServer);
                if (!is_array($pleskServer)) {
                    continue;
                }

                $pleskServerRecords[$pleskServer['uid']] = $this->recordFactory->createFromDatabaseRow(
                    self::TABLE,
                    $pleskServer,
                );
            }
        } catch (Exception $e) {
        }

        return $pleskServerRecords;
    }

    public function getPleskServerRecordsEnum(): array
    {
        $pleskServerRecords = $this->getPleskServerRecords();
        if ($pleskServerRecords === []) {
            return [];
        }

        $optionsForEnum = [
            0 => 'Please select a record',
        ];
        foreach ($pleskServerRecords as $pleskServerRecord) {
            $optionsForEnum[$pleskServerRecord->getUid()] = $pleskServerRecord->get('title');
        }

        return $optionsForEnum;
    }

    public function getPleskServerRecord(int $pleskServerRecordUid): ?Record
    {
        return $this->getPleskServerRecords()[$pleskServerRecordUid] ?? null;
    }

    /**
     * Return TYPO3 QueryBuilder incl. hidden restriction as we do not
     * want to show hidden records in dashboard widget configuration.
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE);
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(HiddenRestriction::class))
            ->add(GeneralUtility::makeInstance(WorkspaceRestriction::class));

        return $queryBuilder;
    }
}
