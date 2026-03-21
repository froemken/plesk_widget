<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Client;

use PleskX\Api\Client;
use Psr\Log\LoggerInterface;
use StefanFroemken\PleskWidget\PasswordPolicy\Validator\PleskPasswordValidator;
use TYPO3\CMS\Core\Crypto\Cipher\CipherService;
use TYPO3\CMS\Core\Crypto\Cipher\CipherValue;
use TYPO3\CMS\Core\Crypto\Cipher\KeyFactory;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3\CMS\Core\Utility\MathUtility;

readonly class PleskClientFactory
{
    public function __construct(
        private LoggerInterface $logger,
        private CipherService $cipherService,
        private KeyFactory $keyFactory,
    ) {}

    /**
     * @throws ExtensionSettingException
     */
    public function create(Record $record): Client
    {
        if ($this->validateRecord($record) === false) {
            throw new ExtensionSettingException(
                'Incomplete plesk widget extension settings. See logs for more details',
                1736610959,
            );
        }

        $encryptedPassword = $record->get('password');
        if ($encryptedPassword !== '') {
            try {
                $secretKey = $this->keyFactory->deriveSharedKeyFromEncryptionKey(PleskPasswordValidator::ENCRYPTION_SEED);
                $cipherValue = CipherValue::fromSerialized($encryptedPassword);
                $password = $this->cipherService->decrypt($cipherValue, $secretKey);
            } catch (\Exception $e) {
                // If decryption fails, we assume the password is still in plain text
                // (e.g. for records created before this feature was introduced).
                $this->logger->debug('Failed to decrypt Plesk password: ' . $e->getMessage());
            }
        }

        $pleskClient = new Client(
            $record->get('host'),
            (int)$record->get('port'),
        );
        $pleskClient->setCredentials(
            $record->get('username'),
            $password,
        );

        return $pleskClient;
    }

    private function validateRecord(Record $record): bool
    {
        if ($record->get('host') === '') {
            $this->logger->error('Plesk host must not be empty');
            return false;
        }

        if (!MathUtility::canBeInterpretedAsInteger($record->get('port'))) {
            $this->logger->error('Plesk server port must be a valid integer');
            return false;
        }

        if ($record->get('username') === '') {
            $this->logger->error('Plesk username must not be empty');
            return false;
        }

        if ($record->get('password') === '') {
            $this->logger->error('Plesk password must not be empty');
            return false;
        }

        return true;
    }
}
