General LDAP Client Usage
===================

* [Connecting and Binding](#connecting-and-binding)
* [Encrypting Your Connection](#encrypting-your-connection)
* [Requests and Responses](#requests-and-responses)
* [Using Controls](#using-controls)
* [WhoAmI](#whoami)
* [Unbinding](#unbinding)

The LdapClient class is your main point for sending requests to LDAP and receiving responses from the server. This details
some general information on using the class to connect, encrypt, bind, and send operations to the server. The class also
contains helpers for searching that are covered in other docs.

## Connecting and Binding

```php
use FreeDSx\Ldap\LdapClient;
use FreeDSx\Ldap\Exception\BindException;

# Construct the LDAP client with an array of options...
$ldap = new LdapClient([
    # The base_dn as the default for all searches (if not explicitly defined)
    'base_dn' => 'dc=domain,dc=local',
    # An array of servers to try to connect to
    'servers' => ['dc01', 'dc02'],
]);

# Bind to LDAP with a username and password.
try {
    $ldap->bind('user@domain.local', '12345');
} catch (BindException $e) {
   echo sprintf('Error (%s): %s', $e->getCode(), $e->getMessage());
   exit;
}
```

Note that the above binds to LDAP without first encrypting your connection. You should issue a StartTLS via `startTls()`
prior to binding to LDAP. See below for details. 

## Encrypting Your Connection

To encrypt your connection using TLS you must issue a StartTLS after constructing your client:

```php
use FreeDSx\Ldap\LdapClient;

$ldap = new LdapClient(['servers' => ['dc01.domain.local']]);

# Issue a StartTLS before doing anything else
$ldap->startTls();
```

To validate your certificate you will need to pass the path to a trusted certificate authority cert to the `ssl_ca_cert`
option when constructing the client:

```php
use FreeDSx\Ldap\LdapClient;

# Pass a path of a trusted CA certificate for validation:
$ldap = new LdapClient(['servers' => ['dc01.domain.local'], 'ssl_ca_cert' => '/path/to/cert.pem']);
$ldap->startTls();

# Connect via TLS but disable security certificate checking (useful for troubleshooting):
$ldap = new LdapClient(['servers' => ['dc01.domain.local'], 'ssl_validate_cert' => false]);
$ldap->startTls();
```

## Requests and Responses

When you send a request to LDAP using the client, it wraps the request in a message object and sends it to the LDAP server.
Most requests will send a response back to the client. This response contains:

* The unique message ID for the request.
* The request object, which contains: The result code, DN related to the request, and the diagnostic message (if any)
* Any controls returned by the server for that request.

These are all available when you send a request to LDAP and get a response. For example, a request to add an entry to LDAP:

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Entry\Entry;

# Use the 'send()' method to send an operation request to LDAP
# We also use the 'add()' factory method for Operations and construct an Entry object 
$message = $ldap->send(Operations::add(Entry::create('cn=foo,dc=domain,dc=local', [
    'objectClass' => ['top', 'group'],
    'sAMAccountName' => 'foo',
])));

$response = $message->getResponse();
echo "Message ID: ".$message->getMessageId().PHP_EOL;
echo "Result Code: ".$response->getResultCode().PHP_EOL;
echo "Diagnostic: ".$response->getDiagnosticMessage().PHP_EOL;
echo "DN: ".$response->getDn().PHP_EOL;

# Check each control returned by the server...
foreach($message->controls() as $control) {
    echo "Control: ".$control->getTypeOid().PHP_EOL;
}
```

If the request is not successful it will throw an OperationException: `FreeDSx\Ldap\Exception\OperationException`. The
exception has the code set to the result code LDAP returned and the exception message will be the diagnostic message
returned from the server.

## Using Controls

Using specific LDAP controls you can affect the way certain LDAP operations happen. There are two ways to send controls:

* A global set of controls that are sent with every request going to LDAP.
* A set of controls that are sent for a specific request only.

Setting a control to be sent for every request:

```php
use FreeDSx\Ldap\Controls;

# Use the factory method of the Controls class to construct a control to set globally on the client.
# The SDFlags control is specific to Active Directory and impacts the Security Descriptor.
$ldap->controls()->add(Controls::sdFlags(7));
```

Send a control along with a specific request:

```php
use FreeDSx\Ldap\Controls;
use FreeDSx\Ldap\Operations;

# Send a delete request with a subtree delete control (deletes all children below the entry...)
$ldap->send(Operations::delete('ou=foo,dc=domain,dc=local'), Controls::subtreeDelete());
```

## WhoAmI

Sometimes it may be useful to see who the LDAP server sees as being bound in the current session. You can use the
'whoami()' method of the client to get the string response of the username/dn from the server:

```php
echo "Connected as: ".$ldap->whoami().PHP_EOL;
```

The string will either be prefixed with a "dn:" or a "u:". This is added by the server, as the WhoAmI response returns
an authzID string.

## Unbinding

When your LDAP client class is destroyed and the destructor is called it will send an unbind request to the server, which
is a polite way to tell the LDAP server that you are disconnecting from it. You can also unbind your client manually:

```php
# Sends a unbind request to the server and terminates the TCP connection
$ldap->unbind();
```
