<?php
/**
 * Created by IntelliJ IDEA.
 * User: christian
 * Date: 15/01/16
 * Time: 08:19
 */

namespace CrateTest\PDO\Http;

use Crate\PDO\Exception\UnsupportedException;
use Crate\PDO\Http\Server;
use GuzzleHttp\Client as HttpClient;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * Class ServerTest
 *
 * @coversDefaultClass \Crate\PDO\Http\Server
 * @covers ::<!public>
 *
 * @group unit
 */
class ServerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Server $client
     */
    private $server;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        $this->server = new Server('http://localhost:4200/_sql', []);
        $this->client = $this->getMock(HttpClient::class);

        $reflection = new ReflectionClass($this->server);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->server, $this->client);
    }

    /**
     * @covers ::getServerInfo
     */
    public function testGetServerInfo()
    {
        $this->setExpectedException(UnsupportedException::class);
        $this->server->getServerInfo();
    }

    /**
     * @covers ::getServerVersion
     */
    public function testGetServerVersion()
    {
        $this->setExpectedException(UnsupportedException::class);
        $this->server->getServerVersion();
    }

    /**
     * @covers ::setTimeout
     */
    public function testSetTimeout()
    {
        $body = ['stmt' => 'select * from sys.cluster',
                 'args' => []];
        $args = [
            null, // uri
            ['json' => $body,
             'headers' => [],
             'timeout' => 4
            ]
        ];
        $this->client
            ->expects($this->once())
            ->method('__call')
            ->with('post', $args);
        $this->server->setTimeout('4');
        $this->server->doRequest($body);
    }

    /**
     * @covers ::setHTTPHeader
     */
    public function testSetHTTPHeader()
    {
        $schema = 'my_schema';
        $schemaHeader = 'Default-Schema';
        $this->server->setHttpHeader($schemaHeader, $schema);


        $body = ['stmt' => 'select * from sys.cluster',
                 'args' => []];
        $args = [
            null, // uri
            ['json' => $body,
             'headers' => [$schemaHeader => $schema],
            ]
        ];
        $this->client
            ->expects($this->once())
            ->method('__call')
            ->with('post', $args);
        $this->server->doRequest($body);
    }

    public function testInitialOptions()
    {
        $this->server = new Server('http://localhost:4200/_sql', ['timeout' => 3]);
        $this->client = $this->getMock(HttpClient::class);

        $reflection = new ReflectionClass($this->server);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->server, $this->client);

        $body = ['stmt' => 'select * from sys.cluster',
                 'args' => []];
        $args = [
            null,
            ['json' => $body,
             'headers' => [],
             'timeout' => 3
            ]
        ];

        $this->client
            ->expects($this->once())
            ->method('__call')
            ->with('post', $args);
        $this->server->doRequest($body);
    }
}
