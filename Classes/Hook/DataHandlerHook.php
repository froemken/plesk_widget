<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Hook;

use TYPO3\CMS\Core\Crypto\Cipher\CipherService;
use TYPO3\CMS\Core\Crypto\Cipher\KeyFactory;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Database\ConnectionPool;

final readonly class DataHandlerHook
{
    private const ENCRYPTION_SEED = 'plesk_widget_password';

    private const TABLE = 'tx_pleskwidget_server';

    public function __construct(
        private CipherService $cipherService,
        private KeyFactory $keyFactory,
        private ConnectionPool $connectionPool,
    ) {}

    /**
     * Encrypt password before storing it to the database.
     */
    public function processDatamap_postProcessFieldArray(
        string $status,
        string $table,
        int|string $id,
        array &$fieldArray,
        DataHandler $dataHandler,
    ): void {
        if ($table !== self::TABLE || !isset($fieldArray['password'])) {
            return;
        }

        $newPassword = (string)$fieldArray['password'];
        if ($newPassword === '') {
            return;
        }

        // For new records, always encrypt
        if ($status === 'new') {
            $fieldArray['password'] = $this->encrypt($newPassword);
            return;
        }

        // For existing records, check if the password has changed.
        // If the user didn't change the password in the form, the encrypted value from the DB
        // is sent back. Comparing it with the DB value avoids double encryption.
        $currentPassword = $this->getCurrentPassword((int)$id);
        if ($newPassword !== $currentPassword) {
            $fieldArray['password'] = $this->encrypt($newPassword);
        }
    }

    private function encrypt(string $password): string
    {
        $key = $this->keyFactory->deriveSharedKeyFromEncryptionKey(self::ENCRYPTION_SEED);

        return (string)$this->cipherService->encrypt($password, $key);
    }

    private function getCurrentPassword(int $uid): string
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE);
        $result = $queryBuilder
            ->select('password')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT),
                )
            )
            ->executeQuery()
            ->fetchOne();

        return (string)$result;
    }
}
