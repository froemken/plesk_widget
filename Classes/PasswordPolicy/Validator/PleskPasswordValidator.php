<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\PasswordPolicy\Validator;

use TYPO3\CMS\Core\PasswordPolicy\Validator\AbstractPasswordValidator;
use TYPO3\CMS\Core\PasswordPolicy\Validator\Dto\ContextData;

class PleskPasswordValidator extends AbstractPasswordValidator
{
    public const ENCRYPTION_SEED = 'plesk_widget_password';

    public const MAX_PASSWORD_LENGTH = 64;

    public function validate(string $password, ?ContextData $contextData = null): bool
    {
        $isValid = true;
        $lang = $this->getLanguageService();

        if (strlen($password) > self::MAX_PASSWORD_LENGTH) {
            $this->addErrorMessage(
                'maximumLength',
                sprintf(
                    $lang->sL('plesk_widget.password_policy:error.maximumLength'),
                    self::MAX_PASSWORD_LENGTH,
                )
            );
            $isValid = false;
        }

        return $isValid;
    }

    public function initializeRequirements(): void
    {
        $lang = $this->getLanguageService();

        $this->addRequirement(
            'maximumLength',
            sprintf(
                $lang->sL('plesk_widget.password_policy:requirement.maximumLength'),
                self::MAX_PASSWORD_LENGTH,
            ),
        );
    }
}
