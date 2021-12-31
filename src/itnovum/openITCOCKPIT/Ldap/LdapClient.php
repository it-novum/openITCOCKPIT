<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Ldap;


use App\Model\Table\LdapgroupsTable;
use App\Model\Table\UsersTable;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class LdapClient {

    /**
     * @var \FreeDSx\Ldap\LdapClient
     */
    private $ldap;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var null|string
     */
    private $rawFilter = null;

    /**
     * @var bool
     */
    private $isOpenLdap = false;

    const ENCRYPTION_PLAIN = 0;

    const ENCRYPTION_STARTTLS = 1;

    const ENCRYPTION_TLS = 2;

    /**
     * LdapClient constructor.
     * @param string $username
     * @param string $passwod
     * @param array $options
     * @param bool $isOpenLdap
     * @throws \FreeDSx\Ldap\Exception\BindException
     */
    private function __construct($username, $passwod, $options = [], $isOpenLdap = false) {
        $this->username = $username;
        $this->password = $passwod;

        $_options = [
            //'servers'             => ['127.0.0.1'],
            'port'                  => 389,
            'ssl_allow_self_signed' => true,
            'ssl_validate_cert'     => false,
            'tls_level'             => 0,
            'base_dn'               => 'DC=example,DC=org'
        ];

        $options = Hash::merge($_options, $options);
        $this->isOpenLdap = $isOpenLdap;


        if ($options['tls_level'] == self::ENCRYPTION_TLS) {
            // Connect through an TLS encrypted connection (ldaps)
            // https://github.com/FreeDSx/LDAP/blob/master/docs/Client/Configuration.md#ssl-and-tls-options
            $options['use_ssl'] = true;
        }


        $this->ldap = new \FreeDSx\Ldap\LdapClient($options);
        if ($options['tls_level'] == self::ENCRYPTION_STARTTLS) {
            // Connection was established as plain text connection - send StartTLS package to upgrade it to an encrypted connection
            $this->ldap->startTls();
        }

        $this->ldap->bind($this->username, $this->password);
    }

    /**
     * @param $systemsettings
     * @return LdapClient
     * @throws \FreeDSx\Ldap\Exception\BindException
     */
    public static function fromSystemsettings($systemsettings) {
        $username = sprintf(
            '%s%s',
            $systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
            $systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX']
        );
        $passwod = $systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD'];

        $self = new self(
            $username,
            $passwod,
            [
                'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                'ssl_allow_self_signed' => true,
                'ssl_validate_cert'     => false,
                'tls_level'             => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                'base_dn'               => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
            ],
            ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap')
        );

        $self->setRawFilter($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']);
        return $self;
    }

    public function setRawFilter($rawFilter) {
        $this->rawFilter = $rawFilter;
    }

    /**
     * @param string $sAMAccountName
     * @return array
     */
    public function getUsers($sAMAccountName = '', $includeMember = false) {


        if ($this->isOpenLdap === false) {
            //MS AD
            $requiredFields = ['samaccountname', 'mail', 'sn', 'givenname'];
            $filter = $this->getUsersFilter($sAMAccountName);
            $search = \FreeDSx\Ldap\Operations::search($filter, 'samaccountname', 'mail', 'sn', 'givenname', 'displayname', 'dn', 'memberOf');
        } else {
            //OpenLDAP
            $requiredFields = ['uid', 'mail', 'sn', 'givenname'];
            $filter = $this->getUsersFilter($sAMAccountName);
            $search = \FreeDSx\Ldap\Operations::search($filter, 'uid', 'mail', 'sn', 'givenname', 'displayname', 'dn', 'memberOf');
        }

        $result = [];
        $paging = $this->ldap->paging($search, 100);

        $droppedUsers = 0;
        $resultCount = 0;
        while ($paging->hasEntries()) {
            foreach ($paging->getEntries() as $entry) {
                $resultCount++;
                $userDn = (string)$entry->getDn();
                if (empty($userDn)) {
                    continue;
                }

                $entry = $entry->toArray();
                $entry = array_combine(array_map('strtolower', array_keys($entry)), array_values($entry));
                foreach ($requiredFields as $requiredField) {
                    if (!isset($entry[$requiredField])) {
                        $droppedUsers++;
                        continue 2;
                    }
                }

                if (isset($entry['uid'])) {
                    $entry['samaccountname'] = $entry['uid'];
                }

                $memberOf = [];
                if ($includeMember) {
                    $memberOf = $entry['memberof'] ?? [];
                }

                $result[] = [
                    'givenname'      => $entry['givenname'][0],
                    'sn'             => $entry['sn'][0],
                    'samaccountname' => $entry['samaccountname'][0],
                    'email'          => $entry['mail'][0],
                    'dn'             => $userDn,
                    'memberof'         => $memberOf,
                    'display_name'   => sprintf(
                        '%s, %s (%s)',
                        $entry['givenname'][0],
                        $entry['sn'][0],
                        $entry['samaccountname'][0]
                    )
                ];
            }
            // Only load the first 100 users.
            // Pass $sAMAccountName to search for a user
            $paging->end();
        }

        if ($droppedUsers > 0) {
            Log::warning(
                sprintf(
                    'Dropped %s/%s AD/LDAP users due to missing required fields. [%s]',
                    $droppedUsers,
                    $resultCount,
                    implode(', ', $requiredFields)
                ));
        }

        return $result;
    }

    /**
     * @param string $sAMAccountName
     * @return \FreeDSx\Ldap\Search\Filter\AndFilter|\FreeDSx\Ldap\Search\Filter\FilterInterface|\FreeDSx\Ldap\Search\Filter\SubstringFilter
     */
    private function getUsersFilter($sAMAccountName = '') {
        if ($this->isOpenLdap) {
            if ($sAMAccountName != '' && $this->rawFilter == '') {
                $filter = \FreeDSx\Ldap\Search\Filters::contains('uid', $sAMAccountName);
            }

            if ($this->rawFilter != '' && $sAMAccountName == '') {
                $filter = \FreeDSx\Ldap\Search\Filters::raw($this->rawFilter);
            }

            //Use filters for OpenLDAP
            $filter = \FreeDSx\Ldap\Search\Filters::contains('uid', $sAMAccountName);

            if ($this->rawFilter != '' && $sAMAccountName != '') {
                $filter = \FreeDSx\Ldap\Search\Filters::and(
                    \FreeDSx\Ldap\Search\Filters::raw($this->rawFilter),
                    \FreeDSx\Ldap\Search\Filters::contains('uid', $sAMAccountName)
                );
            }

            return $filter;
        }

        //MS AD filters
        if ($sAMAccountName != '' && $this->rawFilter == '') {
            $filter = \FreeDSx\Ldap\Search\Filters::contains('sAMAccountName', $sAMAccountName);
        }

        if ($this->rawFilter != '' && $sAMAccountName == '') {
            $filter = \FreeDSx\Ldap\Search\Filters::raw($this->rawFilter);
        }

        if ($this->rawFilter != '' && $sAMAccountName != '') {
            $filter = \FreeDSx\Ldap\Search\Filters::and(
                \FreeDSx\Ldap\Search\Filters::raw($this->rawFilter),
                \FreeDSx\Ldap\Search\Filters::contains('sAMAccountName', $sAMAccountName)
            );
        }

        return $filter;
    }

    /**
     * @param string $sAMAccountName
     * @return array|null
     */
    public function getUser(string $sAMAccountName, bool $includeMember = true) {
        if ($this->isOpenLdap === false) {
            //MS AD
            $filter = $this->getUsersFilter($sAMAccountName);
            $search = \FreeDSx\Ldap\Operations::search($filter, 'samaccountname', 'mail', 'sn', 'givenname', 'displayname', 'dn', 'memberOf');
        } else {
            //OpenLDAP
            $filter = $this->getUsersFilter($sAMAccountName);
            $search = \FreeDSx\Ldap\Operations::search($filter, 'uid', 'mail', 'sn', 'givenname', 'displayname', 'dn', 'memberOf');
        }

        $paging = $this->ldap->paging($search, 1);

        foreach ($paging->getEntries() as $entry) {
            $userDn = (string)$entry->getDn();
            if (empty($userDn)) {
                continue;
            }

            $entry = $entry->toArray();
            $entry = array_combine(array_map('strtolower', array_keys($entry)), array_values($entry));
            if (isset($entry['uid'])) {
                $entry['samaccountname'] = $entry['uid'];
            }

            $memberOf = [];
            if ($includeMember) {
                $memberOf = $entry['memberof'] ?? [];
            }

            $user = [
                'givenname'      => $entry['givenname'][0],
                'sn'             => $entry['sn'][0],
                'samaccountname' => $entry['samaccountname'][0],
                'email'          => $entry['mail'][0],
                'dn'             => $userDn,
                'memberof'         => $memberOf,
                'display_name'   => sprintf(
                    '%s, %s (%s)',
                    $entry['givenname'][0],
                    $entry['sn'][0],
                    $entry['samaccountname'][0]
                )
            ];

            // Load LDAP groups  from database
            $user['ldapgroups'] = [];
            if(!empty($memberOf)) {
                /** @var LdapgroupsTable $LdapgroupsTable  */
                $LdapgroupsTable = TableRegistry::getTableLocator()->get('Ldapgroups');
                $user['ldapgroups'] = $LdapgroupsTable->getGroupsByDn($memberOf);
            }

            // Only load the first user.
            $paging->end();
            break;
        }


        if (isset($user)) {
            return $user;
        }

        return null;
    }

    public function getGroups($includeMember = false) {
        // @todo add dynamic filter query to systemsettings
        $filter = \FreeDSx\Ldap\Search\Filters::raw('ObjectClass=Group');
        $search = \FreeDSx\Ldap\Operations::search($filter);

        $result = [];
        $paging = $this->ldap->paging($search, 100);

        while ($paging->hasEntries()) {
            foreach ($paging->getEntries() as $entry) {
                /** @var \FreeDSx\Ldap\Entry\Entry $entry */

                $dn = (string)$entry->getDn();
                if (empty($dn)) {
                    continue;
                }

                $entry = $entry->toArray();
                $name = $entry['cn'][0];
                if (isset($entry['sAMAccountName'][0])) {
                    $name = $entry['sAMAccountName'][0];
                }

                $description = $entry['description'][0] ?? '';
                $member = [];
                if ($includeMember) {
                    $member = $entry['member'] ?? [];
                }

                $result[] = [
                    'cn'          => $name,
                    'dn'          => $dn,
                    'description' => $description,
                    'member'      => $member
                ];
            }

            //Do load all LDAP groups for database import and sync
            //$paging->end();
        }


        return $result;
    }

}
