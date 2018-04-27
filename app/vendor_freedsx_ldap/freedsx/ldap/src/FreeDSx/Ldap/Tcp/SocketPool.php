<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Tcp;

use FreeDSx\Ldap\Exception\ConnectionException;

/**
 * Given a selection of hosts, connect to one and return the TCP Socket.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SocketPool
{
    /**
     * @var array
     */
    protected $options = [
        'servers' => [],
        'port' => 389,
        'timeout_connect' => 1,
    ];

    /**
     * @var array
     */
    protected $tcpOpts = [
        'use_ssl',
        'ssl_validate_cert',
        'ssl_allow_self_signed',
        'ssl_ca_cert',
        'ssl_cert',
        'ssl_peer_name',
        'timeout_connect',
        'timeout_read',
        'port',
    ];

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param null|string $server
     * @return Socket
     * @throws ConnectionException
     */
    public function connect(?string $server = null) : Socket
    {
        if ($server) {
            $servers = [$server];
        } else {
            $servers = is_array($this->options['servers']) ? $this->options['servers'] : [$this->options['servers']];
        }

        $lastEx = null;
        $tcp = null;
        foreach ($servers as $server) {
            try {
                $tcp = Socket::create($server, $this->getTcpOptions());
                break;
            } catch (\Exception $e) {
                $lastEx = $e;
            }
        }

        if ($tcp === null) {
            throw new ConnectionException(sprintf(
                'Unable to connect to server(s): %s',
                implode(',', $servers)
            ), 0, $lastEx);
        }

        return $tcp;
    }

    /**
     * @return array
     */
    protected function getTcpOptions() : array
    {
        $opts = [];

        foreach ($this->tcpOpts as $name) {
            if (isset($this->options[$name])) {
                $opts[$name] = $this->options[$name];
            }
        }

        return $opts;
    }
}
