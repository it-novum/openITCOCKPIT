CHANGELOG
=========

0.4.0 (2018-04-20)
------------------
* Moved ASN.1 to its own library.
* Add a create convenience method to the LdapClient to create a single LDAP entry.
* Add a read convenience method to the LdapClient to return a single LDAP entry.
* Add an update convenience method to the LdapClient to update a single LDAP entry.
* Add a delete convenience method to the LdapClient to delete a single LDAP entry.
* Rename searchRead() and searchList() operation methods to read() and list().
* Throw an exception on referrals by default. Do not allow ignoring them, only following them.

0.3.0 (2018-02-12)
------------------
* Implement referral handling options.
* Add an LDAP URL parser / object based on RFC 4516.
* Honor the timeout_read setting in the LDAP client.
* Better handle remote disconnects / failed reads in the client to prevent hanging under some circumstances.
* Add magic methods to Entry objects for attribute access.
* Add an idle_timeout setting to the LDAP server. Default to 600 seconds.

0.2.0 (2017-12-08)
------------------
* Renamed to FreeDSx to avoid naming confusion with the phpds extension.
* Implement very limited LDAP server functionality.
* Added a string filter parser based on RFC 4515. Allows creating filter objects from arbitrary string filters.
* Added a toString() method on filters for displaying their string filter representation.
* Add a compare() operation method helper to the LdapClient.
* Corrected the ASN1 encoding of the 'not' filter.
* Corrected BER encoding indefinite length partial PDU detection. 
* Be more defensive on RDN creation when parsing the string.
* LDAP clients now throw an OperationException instead of a ProtocolException.
* Added documentation for the client and server.

0.1.0 (2017-10-22)
------------------
* Tagging initial release. Still under heavy development.
