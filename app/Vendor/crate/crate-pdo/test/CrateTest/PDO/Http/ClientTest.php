<?php
/**
 * @author Antoine Hedgcock
 */

namespace CrateTest\PDO\Http;

use Crate\PDO\Exception\RuntimeException;
use Crate\PDO\Exception\UnsupportedException;
use Crate\Stdlib\Collection;
use Crate\PDO\Http\Client;
use Crate\PDO\Http\Server;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Response;
use guzzlehttp\psr7;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\UriInterface;
use ReflectionClass;

/**
 * Class ClientTest
 *
 * @coversDefaultClass \Crate\PDO\Http\Client
 * @covers ::<!public>
 *
 * @group unit
 */
class ClientTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $server;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        $server = 'localhost:4200';
        $this->client = new Client([$server], []);
        $this->server = $this->getMockBuilder(Server::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientReflection = new ReflectionClass($this->client);

        $availableServers = $clientReflection->getProperty('availableServers');
        $availableServers->setAccessible(true);
        $availableServers->setValue($this->client, [$server]);

        $serverPool = $clientReflection->getProperty('serverPool');
        $serverPool->setAccessible(true);
        $serverPool->setValue($this->client, [$server => $this->server]);
    }

    /**
     * @covers ::__construct
     */
    public function testMultiServerConstructor()
    {
        $servers = ['crate1:4200', 'crate2:4200', 'crate3:4200'];
        $client = new Client($servers, []);
        $clientReflection = new ReflectionClass($client);

        $p = $clientReflection->getProperty('availableServers');
        $p->setAccessible(true);
        $availableServers = $p->getValue($client);
        $this->assertEquals(3, count($availableServers));

        $p = $clientReflection->getProperty('serverPool');
        $p->setAccessible(true);
        $serverPool = $p->getValue($client);
        $this->assertEquals(3, count($serverPool));
    }

    /**
     * @covers ::nextServer
     */
    public function testNextServer()
    {
        $servers = ['crate1:4200', 'crate2:4200', 'crate3:4200'];
        $client = new Client($servers, []);
        $clientReflection = new ReflectionClass($client);

        $pAvailServers = $clientReflection->getProperty('availableServers');
        $pAvailServers->setAccessible(true);
        $pNextServer = $clientReflection->getMethod('nextServer');
        $pNextServer->setAccessible(true);

        $this->assertEquals('crate1:4200', $pNextServer->invoke($client));
        $this->assertEquals('crate2:4200', $pNextServer->invoke($client));
        $this->assertEquals('crate3:4200', $pNextServer->invoke($client));
        $this->assertEquals('crate1:4200', $pNextServer->invoke($client));
    }

    /**
     * @covers ::dropServer
     */
    public function testDropServer()
    {
        $servers = ['crate1:4200', 'crate2:4200'];
        $client = new Client($servers, []);
        $clientReflection = new ReflectionClass($client);

        $pAvailServers = $clientReflection->getProperty('availableServers');
        $pAvailServers->setAccessible(true);
        $pDropServer = $clientReflection->getMethod('dropServer');
        $pDropServer->setAccessible(true);

        $this->assertEquals(2, count($pAvailServers->getValue($client)));
        $pDropServer->invoke($client, 'crate2:4200', null);
        $this->assertEquals(1, count($pAvailServers->getValue($client)));
        $this->assertEquals(['crate1:4200'], $pAvailServers->getValue($client));
    }

    /**
     * @covers ::dropServer
     */
    public function testDropLastServer()
    {
        $servers = ['localhost:4200'];
        $client = new Client($servers, []);
        $clientReflection = new ReflectionClass($client);


        $this->setExpectedException(ConnectException::class, "No more servers available, exception from last server: Connection refused.");
        $ex = $this->getMock(ConnectException::class, null,
            ['Connection refused.', $this->getMock(RequestInterface::class), null]);

        $pDropServer = $clientReflection->getMethod('dropServer');
        $pDropServer->setAccessible(true);
        $pDropServer->invoke($client, 'localhost:4200');

        $pRaiseIfNoServers = $clientReflection->getMethod('raiseIfNoMoreServers');
        $pRaiseIfNoServers->setAccessible(true);
        $pRaiseIfNoServers->invoke($client, $ex);
    }

    /**
     * Create a response to be used
     *
     * @param int   $statusCode
     * @param array $body
     *
     * @return Response
     */
    private function createResponse($statusCode, array $body)
    {
        $body = psr7\stream_for(json_encode($body));

        return new Response($statusCode, [], $body);
    }

    /**
     * @covers ::execute
     */
    public function testExecuteWithResponseFailure()
    {
        $code    = 1337;
        $message = 'hello world';

        $this->setExpectedException(RuntimeException::class, $message, $code);

        $request = $this->getMock(RequestInterface::class);
        $request->method('getUri')->willReturn($this->getMock(UriInterface::class));
        $response = $this->createResponse(400, ['error' => ['code' => $code, 'message' => $message]]);

        $exception = ClientException::create($request, $response);

        $this->server
            ->expects($this->once())
            ->method('doRequest')
            ->will($this->throwException($exception));

        $this->client->execute('SELECT ? FROM', ['foo']);
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $body = [
            'cols'     => ['name'],
            'rows'     => [['crate2'],['crate2']],
            'rowcount' => 2,
            'duration' => 0
        ];

        $response = $this->createResponse(200, $body);

        $this->server
            ->expects($this->once())
            ->method('doRequest')
            ->will($this->returnValue($response));

        $result = $this->client->execute('SELECT name FROM sys.nodes', []);

        $this->assertInstanceOf(Collection::class, $result);
    }

    /**
     * @covers ::setTimeout
     */
    public function testSetTimeout()
    {
        $this->server
            ->expects($this->once())
            ->method('setTimeout')
            ->with(4);

        $this->client->setTimeout('4');
    }

    /**
     * @covers ::setDefaultSchema
     */
    public function testSetDefaultSchema()
    {
        $schema = 'my_schema';
        $this->server
            ->expects($this->once())
            ->method('setHttpHeader')
            ->with('Default-Schema', $schema);

        $this->client->setDefaultSchema($schema);
    }
}
