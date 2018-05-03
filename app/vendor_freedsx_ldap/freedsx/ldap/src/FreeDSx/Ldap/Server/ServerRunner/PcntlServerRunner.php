<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Server\ServerRunner;

use FreeDSx\Ldap\Exception\RuntimeException;
use FreeDSx\Ldap\Protocol\ServerProtocolHandler;
use FreeDSx\Ldap\Tcp\SocketServer;

/**
 * Uses PNCTL to fork incoming requests and send them to the server protocol handler.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class PcntlServerRunner implements ServerRunnerInterface
{
    /**
     * @var SocketServer
     */
    protected $server;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!extension_loaded('pcntl')) {
            throw new RuntimeException('The PCNTL extension is needed to fork incoming requests, which is only available on Linux.');
        }
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function run(SocketServer $server)
    {
        $this->server = $server;

        while ($socket = $this->server->accept()) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                throw new RuntimeException('Unable to fork process.');
            } else if ($pid === 0) {
                (new ServerProtocolHandler($socket, $this->options))->handle();
                $this->server->removeClient($socket);
                exit;
            }
        }
    }
}
