LDAP Server Configuration
================

* [General Options](#general-options)
    * [ip](#ip)
    * [port](#port)
    * [idle_timeout](#idle_timeout)
    * [require_authentication](#require_authentication)
    * [allow_anonymous](#allow_anonymous)
    * [request_handler](#request_handler)
* [RootDSE Options](#rootdse-options)
    * [dse_naming_contexts](#dse_naming_contexts)
    * [dse_alt_server](#dse_alt_server)
    * [dse_vendor_name](#dse_vendor_name)
    * [dse_vendor_version](#dse_vendor_version)
* [SSL and TLS Options](#ssl-and-tls-options)
    * [ssl_cert](#ssl_cert)
    * [ssl_cert_key](#ssl_cert_key)
    * [ssl_cert_passphrase](#ssl_cert_passphrase)

The LDAP server is configured through an array of configuration values. The configuration is simply passed to the server
on construction:

```php
use FreeDSx\Ldap\LdapServer;

$ldap = new LdapServer([
    'dse_alt_server' => 'dc2.local',
    'port' => 33389,
]);
```

The following documents these various configuration options and how they impact the server.

## General Options

------------------
#### ip

The IP address to bind and listen to while the server is running. By default it will bind to `0.0.0.0`, which will listen
on all IP addresses of the machine.

**Default**: `0.0.0.0`

------------------
#### port

The port to bind to and accept client connections on. By default this is port 389. Since this port is underneath the
first 1024 ports, it will require administrative access when running the server. You can change this to something higher
than 1024 instead if needed.

**Default**: `389`

------------------
#### idle_timeout

Consider an idle client to timeout after this period of time (in seconds) and disconnect their LDAP session. If set to
-1, the client can idle indefinitely and not timeout the connection to the server.

**Default**: `600`

------------------
#### require_authentication

Whether or not authentication (bind) should be required before an operation is allowed.

**Note**: Certain LDAP operations implicitly do not require authentication: StartTLS, RootDSE requests, WhoAmI

**Default**: `true`

------------------
#### allow_anonymous

Whether or not anonymous binds should be allowed.

**Default**: `false`

------------------
#### request_handler

This should be a string class name that implements `FreeDSx\Ldap\Server\RequestHandler\RequestHandlerInterface`. Server 
request operations are then passed to this class along with the request context.

This request handler is instantiated for each client connection, so there can be no special constructor involved.

**Default**: `FreeDSx\Ldap\Server\RequestHandler\GenericRequestHandler`

## RootDSE Options

------------------
#### dse_naming_contexts

The namingContexts attribute for the RootDSE. 

**Default**: `dc=FreeDSx,dc=local`

------------------
#### dse_alt_server

The altServer attribute for the RootDSE. These should be alternate servers to be used if this one becomes unavailable.

**Default**: `(null)`

------------------
#### dse_vendor_name

The vendorName attribute for the RootDSE.

**Default**: `FreeDSx`

------------------
#### dse_vendor_version

The vendorVersion attribute for the RootDSE.

**Default**: `(null)`

## SSL and TLS Options

------------------
#### ssl_cert

The server certificate to use for clients issuing StartTLS commands to encrypt their TCP session.

**Note**: If no certificate is provided clients will be unable to issue a StartTLS operation.

**Default**: `(null)`

------------------
#### ssl_cert_key

The server certificate private key. This can also be bundled with the certificate in the `ssl_cert` option.

**Default**: `(null)`

------------------
#### ssl_cert_passphrase

The passphrase needed for the server certificate's private key. 

**Default**: `(null)`
