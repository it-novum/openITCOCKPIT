LDAP Client Configuration
================

* [General Options](#general-options)
    * [base_dn](#base_dn)
    * [page_size](#page_size)
    * [port](#port)
    * [servers](#servers)
    * [timeout_connect](#timeout_connect)
    * [timeout_read](#timeout_read)
    * [version](#version)
    * [referral](#referral)
    * [referral_limit](#referral_limit)
    * [referral_chaser](#referral_chaser)
* [SSL and TLS Options](#ssl-and-tls-options)
    * [use_ssl](#use_ssl)
    * [ssl_validate_cert](#ssl_validate_cert)
    * [ssl_ca_cert](#ssl_ca_cert)
    * [ssl_allow_self_signed](#ssl_allow_self_signed)

The LDAP client is configured through an array of configuration values. The configuration is simply passed to the client
on construction:

```php
use FreeDSx\Ldap\LdapClient;

$ldap = new LdapClient([
    'servers' => ['dc1', 'dc2', 'dc3'],
    'timeout_connect' => 1,
]);
```

The following documents these various configuration options and how they impact the client.

## General Options

------------------
#### base_dn

A default base DN to use when searching. This will be used if a base DN is not supplied explicitly in a search.

**Default**: `(null)`

------------------
#### page_size

A default page size to use for paging operations. This will be used if a page size is not explicitly passed on the
client's paging method.

**Default**: `1000`

------------------
#### port

The port to connect to on the LDAP server.

**Default**: `389`

------------------
#### servers

An array of LDAP servers or a single server name as a string. When connecting the servers are tried in order until one 
connects. 

**Default**: `[]`

------------------
#### timeout_connect

The timeout period (in seconds) when connecting to an LDAP server initially.

**Default**: `3`

------------------
#### timeout_read

The timeout period (in seconds) when reading data from a server.

**Default**: `10`

------------------
#### version

The LDAP version to use.

**Note**: This library was designed around version 3 only. Changing this may produce unexpected behavior.

**Default**: `3`

------------------
#### referral

The referral handling strategy to use. It must be one of:

* `throw`: When a referral is encountered it throws a ReferralException, which contains the referral object(s).
* `follow`: Referrals will be followed until a result is found or the `referral_limit` is reached.  

When you choose to follow referrals, it will bind to the referral destination using your previous bind request (if there
was one). If you need more control over the bind or what referrals are followed then use the `referral_chaser` option.

**Default**: `throw`

------------------
#### referral_limit

The limit to the number of referrals to follow while trying to complete a request. Once this limit is reached an
OperationException with a code of referral is thrown. 

**Default**: 10

------------------
#### referral_chaser

Use this with the referral option set to `follow`. Set this option to a class implementing `FreeDSx\Ldap\ReferralChaserInterface`.
You must implement two methods:

```php
public function chase(LdapMessageRequest $request, LdapUrl $referral, ?BindRequest $bind) : ?BindRequest;

public function client(array $options) : LdapClient;
```

Using this you can implement your own logic for whether or not to follow a referral and what credentials should be used.
You can skip a referral by throwing `FreeDSx\Ldap\Exception\SkipReferralException`. If you skip all referrals then a 
ReferralException will be thrown.

Using the `client($options)` method you can control how your LdapClient is constructed for the referral and perform any
needed logic beforehand, such as a StartTLS command.

**Default**: `null`

## SSL and TLS Options

------------------
#### use_ssl

If set to true, the client will use an SSL stream to connect to the server. This would mostly be used for servers running
over port 636 using SSL only. You still must change the port number if you choose this option.

**Note**: LDAP over SSL (port 636), commonly referred to as LDAPS, has been deprecated. You should use StartTLS instead. 

**Default**: `false`

------------------
#### ssl_validate_cert

If this is set to false then no LDAP server certificate validation is performed when connecting via StartTLS or SSL.
This can be useful for trouble shooting, but it is recommended to set the certificate with `ssl_ca_cert` and keep this
set to true.

**Default**: `true`

------------------
#### ssl_ca_cert

The full path to the trusted CA certificate for the LDAP server certificate. This is used for SSL certificate validation
when connecting over StartTLS or SSL. 

**Default**: `(null)`

------------------
#### ssl_allow_self_signed

Whether or not self-signed certificates are valid when LDAP server certificate validation is done.

**Default**: `false`
