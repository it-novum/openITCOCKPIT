<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap;

use FreeDSx\Ldap\Server\ServerRunner\PcntlServerRunner;
use FreeDSx\Ldap\Server\ServerRunner\ServerRunnerInterface;
use FreeDSx\Ldap\Tcp\SocketServer;

/**
 * The LDAP server.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class LdapServer
{
    /**
     * @var array
     */
    protected $options = [
        'ip' => '0.0.0.0',
        'port' => 389,
        'idle_timeout' => 600,
        'require_authentication' => true,
        'allow_anonymous' => false,
        'request_handler' => null,
        'ssl_cert' => null,
        'ssl_cert_passphrase' => null,
        'dse_alt_server' => null,
        'dse_naming_contexts' => 'dc=FreeDSx,dc=local',
        'dse_vendor_name' => 'FreeDSx',
        'dse_vendor_version' => null,
    ];

    /**
     * @var ServerRunnerInterface
     */
    protected $runner;

    /**
     * @param array $options
     * @param ServerRunnerInterface|null $serverRunner
     */
    public function __construct(array $options = [], ServerRunnerInterface $serverRunner = null)
    {
        $this->options = array_merge($this->options, $options);
        $this->runner = $serverRunner ?? new PcntlServerRunner($this->options);
    }

    /**
     * Runs the LDAP server. Binds the socket on the request IP/port and sends it to the server runner.
     */
    public function run()
    {
        $this->runner->run(SocketServer::bind($this->options['ip'], $this->options['port'], $this->options));
    }
}
