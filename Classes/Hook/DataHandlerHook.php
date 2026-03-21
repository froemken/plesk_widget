<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Hook;

use StefanFroemken\PleskWidget\PasswordPolicy\Validator\PleskPasswordValidator;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Crypto\Cipher\CipherService;
use TYPO3\CMS\Core\Crypto\Cipher\KeyFactory;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\PasswordPolicy\PasswordPolicyAction;
use TYPO3\CMS\Core\PasswordPolicy\PasswordPolicyValidator;
use TYPO3\CMS\Core\Schema\TcaSchemaFactory;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final readonly class DataHandlerHook
{
    private const TABLE = 'tx_pleskwidget_server';

    public function __construct(
        private CipherService $cipherService,
        private KeyFactory $keyFactory,
        private FlashMessageService $flashMessageService,
        private LanguageServiceFactory $languageServiceFactory,
        private TcaSchemaFactory $tcaSchemaFactory,
    ) {}

    /**
     * Validates the password field before a record is processed.
     * If the password validation fails, the record saving is aborted by setting
     * the incomingFieldArray to null. Consequently, the encryption in
     * processDatamap_postProcessFieldArray is not triggered.
     */
    public function processDatamap_preProcessFieldArray(
        array &$incomingFieldArray,
        string $table,
        int|string $id,
        DataHandler $dataHandler,
    ): void {
        if ($table !== self::TABLE || !isset($incomingFieldArray['password'])) {
            return;
        }

        $tcaFieldConf = $this->getTcaConfigurationForField(
            self::TABLE,
            'password',
            $incomingFieldArray,
        );

        $passwordPolicy = $tcaFieldConf['passwordPolicy'] ?? '';
        $passwordPolicyValidator = GeneralUtility::makeInstance(
            PasswordPolicyValidator::class,
            PasswordPolicyAction::NEW_USER_PASSWORD,
            is_string($passwordPolicy) ? $passwordPolicy : '',
        );

        if (!$passwordPolicyValidator->isValidPassword($incomingFieldArray['password'])) {
            $defaultFlashMessageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
            $defaultFlashMessageQueue->enqueue($this->getErrorFlashMessage(
                sprintf(
                    $this->getLanguageService()->sL('plesk_widget.password_policy:error.maximumLength'),
                    PleskPasswordValidator::MAX_PASSWORD_LENGTH,
                ),
            ));

            $incomingFieldArray = null;
        }
    }

    /**
     * Encrypt password before storing it to the database.
     */
    public function processDatamap_postProcessFieldArray(
        string $cmd,
        string $table,
        int|string $id,
        bool|null|array &$fieldArray,
        DataHandler $dataHandler,
    ): void {
        if (!is_array($fieldArray) || $table !== self::TABLE || !isset($fieldArray['password'])) {
            return;
        }

        $newPassword = (string)$fieldArray['password'];
        if ($newPassword === '') {
            return;
        }

        $fieldArray['password'] = $this->encrypt($newPassword);
    }

    private function encrypt(string $password): string
    {
        $key = $this->keyFactory->deriveSharedKeyFromEncryptionKey(PleskPasswordValidator::ENCRYPTION_SEED);

        return (string)$this->cipherService->encrypt($password, $key);
    }

    private function getErrorFlashMessage(string $message): FlashMessage
    {
        return GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            '',
            ContextualFeedbackSeverity::ERROR,
            true,
        );
    }

    private function getLanguageService(): LanguageService
    {
        if (($GLOBALS['LANG'] ?? null) instanceof LanguageService) {
            return $GLOBALS['LANG'];
        }

        return $this->languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER'] ?? null);
    }

    private function getTcaConfigurationForField(
        string $table,
        string $field,
        array $currentRecord,
    ): array {
        $schema = $this->tcaSchemaFactory->get($table);
        $recordType = BackendUtility::getTCAtypeValue($table, $currentRecord);
        if ($schema->hasSubSchema($recordType) && $schema->getSubSchema($recordType)->hasField($field)) {
            return $schema->getSubSchema($recordType)->getField($field)->getConfiguration();
        }

        return $schema->getField($field)->getConfiguration();
    }
}
