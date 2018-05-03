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

use FreeDSx\Ldap\Tcp\SocketServer;

/**
 * Runs the TCP server, accepts client connections, dispatches client connections to the server protocol handler.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
interface ServerRunnerInterface
{
    /**
     * Runs the socket server to accept incoming client connections and dispatch them to the protocol handler.
     *
     * @param SocketServer $server
     */
    public function run(SocketServer $server);
}
