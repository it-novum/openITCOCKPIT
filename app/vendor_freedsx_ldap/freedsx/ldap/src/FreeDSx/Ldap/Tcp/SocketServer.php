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
 * TCP socket server to accept client connections.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SocketServer extends Socket
{
    /**
     * @var array
     */
    protected $serverOpts = [
        'ssl_cert' => null,
        'ssl_cert_key' => null,
        'ssl_cert_passphrase' => null,
        'ssl_crypto_type' => STREAM_CRYPTO_METHOD_TLSv1_2_SERVER | STREAM_CRYPTO_METHOD_TLSv1_1_SERVER | STREAM_CRYPTO_METHOD_TLS_SERVER,
        'ssl_validate_cert' => false,
        'idle_timeout' => 600,
    ];

    /**
     * @var resource
     */
    protected $context;

    /**
     * @var Socket[]
     */
    protected $clients = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct(null, array_merge($this->serverOpts, $options));
    }

    /**
     * Create the socket server and bind to a specific port to listen for clients.
     *
     * @param string $ip
     * @param int $port
     * @return $this
     * @throws ConnectionException
     * @internal param string $ip
     */
    public function listen(string $ip, int $port)
    {
        $this->socket = @stream_socket_server(
            'tcp://'.$ip.':'.$port,
            $this->errorNumber,
            $this->errorMessage,
            STREAM_SERVER_LISTEN | STREAM_SERVER_BIND,
            $this->createSocketContext()
        );
        if (!$this->socket) {
            throw new ConnectionException(sprintf(
                'Unable to open TCP socket (%s): %s',
                $this->errorNumber,
                $this->errorMessage
            ));
        }

        return $this;
    }

    /**
     * @param int $timeout
     * @return null|Socket
     */
    public function accept($timeout = -1) : ?Socket
    {
        $socket = @stream_socket_accept($this->socket, $timeout);
        if ($socket) {
            $socket = new Socket($socket, array_merge($this->options, [
                'timeout_read' => $this->options['idle_timeout']
            ]));
            $this->clients[] = $socket;
        }

        return $socket instanceof Socket ? $socket : null;
    }

    /**
     * @return Socket[]
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param Socket $socket
     */
    public function removeClient(Socket $socket)
    {
        if (($index = array_search($socket, $this->clients, true)) !== false) {
            unset($this->clients[$index]);
        }
    }

    /**
     * Create the socket server. Binds and listens on a specific
     * @param string $ip
     * @param int $port
     * @param array $options
     * @return $this
     */
    public static function bind(string $ip, int $port, array $options = [])
    {
        return (new self($options))->listen($ip, $port);
    }
}
