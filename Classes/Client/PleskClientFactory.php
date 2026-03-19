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
use StefanFroemken\PleskWidget\Service\PleskServerRecordService;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3\CMS\Core\Utility\MathUtility;

readonly class PleskClientFactory
{
    public function __construct(
        private PleskServerRecordService $pleskServerRecordService,
        private LoggerInterface $logger,
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

        $pleskClient = new Client(
            $record->get('host'),
            (int)$record->get('port'),
        );
        $pleskClient->setCredentials(
            $record->get('username'),
            $record->get('password'),
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
