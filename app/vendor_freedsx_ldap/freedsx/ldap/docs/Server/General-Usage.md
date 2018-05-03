General LDAP Server Usage
===================

* [Running the Server](#running-the-server)
* [Handling Client Requests](#handling-client-requests)
  * [Proxy Request Handler](#proxy-request-handler)
  * [Generic Request Handler](#generic-request-handler)
* [StartTLS SSL Certificate Support](#starttls-ssl-certificate-support)

The LdapServer class is used to run a LDAP server process that accepts client requests and sends back a response. It
defaults to using a forking method for client requests, which is only available on Linux.

The LDAP server has no entry database/schema persistence by itself. It is currently up to the implementor to determine 
how to create / update / delete / search for entries that are requested from the client. See the [Handling Client Requests](#handling-client-requests)
section for more details.

## Running The Server

In its most simple form you can run the LDAP server by constructing the class and calling the `run()` method. This will
bind to port 389 to accept clients from any IP on the server. It will use the GenericRequestHandler which by default 
rejects client requests for any operation. 

```php
use FreeDSx\Ldap\LdapServer;

$server = (new LdapServer())->run();
```

## Handling Client Requests

When the server receives a client request it will get sent to the request handler defined for the server. There are only
a few types of requests not sent to the request handler:

* WhoAmI
* StartTLS
* RootDSE
* Unbind

All other requests are sent to handler you define. The handler must implement `FreeDSx\Ldap\Server\RequestHandler\RequestHandlerInterface`.
The interface has the following methods:

```php
    public function add(RequestContext $context, AddRequest $add);

    public function compare(RequestContext $context, CompareRequest $compare) : bool;
    
    public function delete(RequestContext $context, DeleteRequest $delete);

    public function extended(RequestContext $context, ExtendedRequest $extended);

    public function modify(RequestContext $context, ModifyRequest $modify);

    public function modifyDn(RequestContext $context, ModifyDnRequest $modifyDn);

    public function search(RequestContext $context, SearchRequest $search) : Entries;
    
    public function bind(string $username, string $password) : bool;
```

However, there is a generic request handler you can extend to implement only what you want. Or a proxy handler to forward
requests to a separate LDAP server.

### Proxy Request Handler

The proxy request handler simply forwards the LDAP request from the FreeDSx server to a different LDAP server, then sends
the response back to the client. You should extend the ProxyRequestHandler class and add your own client options to be
used.

1. Create your own class extending the ProxyRequestHandler:

```php
namespace Foo;

use FreeDSx\Ldap\Server\RequestHandler\ProxyRequestHandler;

class LdapProxyHandler extends ProxyRequestHandler
{
    /**
     * Set the options for the LdapClient in the constructor.
     */
    public function __construct()
    {
        $this->options = [
            'servers' => ['dc1.domain.local', 'dc2.domain.local'],
            'base_dn' => 'dc=domain,dc=local',
        ];
    }
}
```

2. Create the server and run it with the request handler above: 

```php
use FreeDSx\Ldap\LdapServer;
use Foo\LdapProxyHandler;

$server = new LdapServer([ 'request_handler' => LdapProxyHandler::class ]);
$server->run();
```

### Generic Request Handler

The generic request handler implements the needed RequestHandlerInterface, but rejects all request types by default. You
should extend this class and override the methods for the requests you want to support:

1. Create your own class extending the GenericRequestHandler:

```php
namespace Foo;

use FreeDSx\Ldap\Server\RequestHandler\GenericRequestHandler;

class LdapRequestHandler extends GenericRequestHandler
{
    /**
     * @var array
     */
    protected $users = [
        'user' => '12345',
    ];

    /**
     * Validates the username/password of a simple bind request
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function bind(string $username, string $password): bool
    {
        return isset($this->users[$username]) && $this->users[$username] === $password;
    }

    /**
     * Override the search request. This must send back an entries object.
     *
     * @param RequestContext $context
     * @param SearchRequest $search
     * @return Entries
     */
    public function search(RequestContext $context, SearchRequest $search): Entries
    {
        return new Entries(
            Entry::create('cn=Foo,dc=FreeDSx,dc=local', [
                'cn' => 'Foo',
                'sn' => 'Bar',
                'givenName' => 'Foo',
            ]),
            Entry::create('cn=Chad,dc=FreeDSx,dc=local', [
                'cn' => 'Chad',
                'sn' => 'Sikorra',
                'givenName' => 'Chad',
            ])
        );
    }
}
```

2. Create the server and run it with the request handler above: 

```php
use FreeDSx\Ldap\LdapServer;
use Foo\LdapRequestHandler;

$server = new LdapServer([ 'request_handler' => LdapRequestHandler::class ]);
$server->run();
```

## StartTLS SSL Certificate Support

To allow clients to issue a StartTLS command against the LDAP server you need to provide an SSL certificate, key, and
key passphrase/password (if needed) when constructing the server class. If these are not present then the StartTLS 
request will not be supported.

Adding the generated certs and keys on construction:

```php
use FreeDSx\Ldap\LdapServer;

$server = new LdapServer([
    # The key can also be bundled in this cert
    'ssl_cert' => '/path/to/cert.pem',
    # The key for the cert. Not needed if bundled above.
    'ssl_cert_key' => '/path/to/cert.key',
    # The password/passphrase to read the key (if required)
    'ssl_cert_passphrase' => 'This-Is-My-Secret-Password',
]);

$server->run();
```
