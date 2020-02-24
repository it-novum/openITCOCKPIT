<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Identifier;

use App\Model\Table\UsersTable;
use Authentication\Identifier\AbstractIdentifier;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Identifier\Resolver\ResolverAwareTrait;
use Authentication\Identifier\Resolver\ResolverInterface;
use Authentication\PasswordHasher\PasswordHasherFactory;
use Authentication\PasswordHasher\PasswordHasherInterface;
use Authentication\PasswordHasher\PasswordHasherTrait;
use Cake\ORM\TableRegistry;

/**
 * Password Identifier
 *
 * Identifies authentication credentials with password
 *
 * ```
 *  new PasswordIdentifier([
 *      'fields' => [
 *          'username' => ['username', 'email'],
 *          'password' => 'password'
 *      ]
 *  ]);
 * ```
 *
 * When configuring PasswordIdentifier you can pass in config to which fields,
 * model and additional conditions are used.
 */
class PasswordIdentifier extends AbstractIdentifier implements IdentifierInterface {

    use PasswordHasherTrait {
        getPasswordHasher as protected _getPasswordHasher;
    }
    use ResolverAwareTrait;

    /**
     * Default configuration.
     * - `fields` The fields to use to identify a user by:
     *   - `username`: one or many username fields.
     *   - `password`: password field.
     * - `resolver` The resolver implementation to use.
     * - `passwordHasher` Password hasher class. Can be a string specifying class name
     *    or an array containing `className` key, any other keys will be passed as
     *    config to the class. Defaults to 'Default'.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields'         => [
            self::CREDENTIAL_USERNAME => 'username',
            self::CREDENTIAL_PASSWORD => 'password',
        ],
        'passwordHasher' => null,
    ];

    /**
     * Return password hasher object.
     *
     * @return \Authentication\PasswordHasher\PasswordHasherInterface Password hasher instance.
     */
    public function getPasswordHasher(): PasswordHasherInterface {
        if ($this->_passwordHasher === null) {
            $passwordHasher = $this->getConfig('passwordHasher');
            if ($passwordHasher !== null) {
                $passwordHasher = PasswordHasherFactory::build($passwordHasher);
            } else {
                $passwordHasher = $this->_getPasswordHasher();
            }
            $this->_passwordHasher = $passwordHasher;
        }

        return $this->_passwordHasher;
    }

    /**
     * @inheritdoc
     */
    public function identify(array $data) {
        if (!isset($data[self::CREDENTIAL_USERNAME])) {
            return null;
        }

        $identity = $this->_findIdentity($data[self::CREDENTIAL_USERNAME]);
        if (array_key_exists(self::CREDENTIAL_PASSWORD, $data)) {
            $password = $data[self::CREDENTIAL_PASSWORD];
            if (!$this->_checkPassword($identity, $password)) {
                return null;
            }
        }

        return $identity;
    }

    /**
     * Find a user record using the username and password provided.
     * Input passwords will be hashed even when a user doesn't exist. This
     * helps mitigate timing attacks that are attempting to find valid usernames.
     *
     * @param array|\ArrayAccess|null $identity The identity or null.
     * @param string|null $password The password.
     * @return bool
     */
    protected function _checkPassword($identity, ?string $password): bool {
        $passwordField = $this->getConfig('fields.' . self::CREDENTIAL_PASSWORD);

        if ($identity === null) {
            $identity = [
                $passwordField => '',
            ];
        }

        $hasher = $this->getPasswordHasher();
        $hashedPassword = $identity[$passwordField];
        if (!$hasher->check((string)$password, $hashedPassword)) {
            return false;
        }

        $this->_needsPasswordRehash = $hasher->needsRehash($hashedPassword);

        return true;
    }

    /**
     * Find a user record using the username/identifier provided.
     *
     * @param string $identifier The username/identifier.
     * @return \ArrayAccess|array|null
     */
    protected function _findIdentity(string $identifier) {
        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        return $UsersTable->getUserByEmailForLogin($identifier);
    }
}
