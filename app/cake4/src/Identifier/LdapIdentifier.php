<?php


namespace App\Identifier;

use App\Model\Table\SystemsettingsTable;
use App\Model\Table\UsersTable;
use Authentication\Identifier\IdentifierInterface;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use FreeDSx\Ldap\Exception\BindException;
use FreeDSx\Ldap\LdapClient;
use FreeDSx\Ldap\Operation\ResultCode;
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;

class LdapIdentifier implements IdentifierInterface {

    /**
     * List of errors
     *
     * @var array
     */
    protected $_errors = [];

    /**
     * Identifies an user or service by the passed credentials
     *
     * @param array $credentials Authentication credentials
     * @return \ArrayAccess|array|null
     */
    public function identify(array $credentials) {
        if (!isset($credentials['username'])) {
            //or username === null
            return null;
        }

        $identity = $this->_findIdentity($credentials['username']);
        if (array_key_exists('password', $credentials) && $identity !== null) {
            $password = $credentials['password'];
            if (!$this->_checkPassword($identity, $password)) {
                return null;
            }
        }

        return $identity;
    }

    /**
     * Gets a list of errors happened in the identification process
     *
     * @return array
     */
    public function getErrors(): array {
        return $this->_errors;
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
        return $UsersTable->getUserBySamAccountName($identifier);
    }

    /**
     * Connect to LDAP Server and check provided credentials
     *
     * @param array|\ArrayAccess|null $identity The identity or null.
     * @param string|null $password The password.
     * @return bool
     */
    protected function _checkPassword($identity, ?string $password): bool {
        if (empty($password)) {
            return false;
        }

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArraySection('FRONTEND');

        //Connect to LDAP Server
        try {
            $ldap = new LdapClient([
                'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                'ssl_allow_self_signed' => true,
                'ssl_validate_cert'     => false,
                'use_tls'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                'base_dn'               => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
            ]);
            if ((bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS']) {
                $ldap->startTls();
            }


            $ldap->bind(
                sprintf(
                    '%s%s',
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX']
                ),
                $systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD']
            );
        } catch (\Exception $e) {
            Log::error($e);
            $this->_errors[] = $e->getMessage();
            return false;
        }

        //Set Filters
        $filter = Filters::and(
            Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
            Filters::equal('sAMAccountName', $identity->get('samaccountname'))
        );
        if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
            $filter = Filters::and(
                Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                Filters::equal('dn', $identity->get('samaccountname'))
            );
        }

        $search = Operations::search($filter, 'cn', 'memberof', 'dn');

        /** @var \FreeDSx\Ldap\Entry\Entries $entries */
        $entries = $ldap->search($search);

        $userDn = null;
        foreach ($entries as $entry) {
            /** @var \FreeDSx\Ldap\Entry\Entry $entry */

            $userDn = (string)$entry->getDn();
            $ldap->unbind(); //Remove ldap search account

            $ldap = new LdapClient([
                # Servers are tried in order until one connects
                'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                'ssl_allow_self_signed' => true,
                'ssl_validate_cert'     => false,
                'use_tls'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                'base_dn'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
            ]);
            if ((bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS']) {
                $ldap->startTls();
            }

            if (!empty($userDn)) {
                try {
                    $ldap->bind($userDn, $password);
                    return true; // valid credentials :)
                } catch (BindException $e) {
                    Log::error($e);
                    if ($e->getCode() === ResultCode::INVALID_CREDENTIALS) {
                        $this->_errors[] = __('Invalid username or password');
                        return false;
                    }

                    $this->_errors[] = $e->getMessage();
                    return false;
                } catch (\Exception $e) {
                    Log::error($e);
                    $this->_errors[] = $e->getMessage();
                    return false;
                }
            }
        }
        return false;
    }
}
