Client Operations
================

The client can send any LDAP operation to the server through various request objects. There are some convenience methods
for common operations, along with factory methods for standard operations:

* [Add Request](#add-request)
* [Delete Request](#delete-request)
* [Modify Request](#modify-request)
* [Rename Request](#rename-request)
* [Move Request](#move-request)
* [Search Request](#search-request)
* [Compare Request](#compare-request)
* [Password Modify Request](#password-modify-request)

All of these operations are constructed with methods on the `FreeDSx\Ldap\Operations` class.

## Add Request

Add an entry to LDAP and catch an operation exception:

```php
use FreeDSx\Ldap\Entry\Entry;
use FreeDSx\Ldap\Exception\OperationException;

# Create a new LDAP entry object using a simple array of values
$entry = Entry::create('cn=foo,dc=domain,dc=local', [
    'objectClass' => ['top', 'group'],
    'sAMAccountName' => 'foo',
]);

# Add the entry to LDAP by passing it to the add request and sending it with the client
try {
    $ldap->create($entry);
} catch (OperationException $e) {
    echo sprintf('Error adding entry (%s): %s', $e->getCode(), $e->getMessage());
}
```

## Delete Request

Delete an entry from LDAP using its distinguished name and catch an operation exception:

```php
use FreeDSx\Ldap\Exception\OperationException;

# Delete an entry using its DN. This can also be a DN object from an entry: $entry->getDn()
try {
    $ldap->delete('cn=foo,dc=domain,dc=local');
} catch (OperationException $e) {
    echo sprintf('Error deleting entry (%s): %s', $e->getCode(), $e->getMessage());
}
```

Delete entries using the results of a search:

```php
use FreeDSx\Ldap\Exception\OperationException;
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;

$entries = $ldap->search(Operations::search(Filters::contains('title', 'manager')));

foreach ($entries as $entry) {
    try {
        $ldap->delete($entry);
    } catch (OperationException $e) {
        echo sprintf('Error deleting entry "%s" (%s): %s', $entry, $e->getCode(), $e->getMessage());
    }
}
```

## Modify Request

Modify an entry object by deleting/adding/removing attribute values and catch an operation exception:

```php
use FreeDSx\Ldap\Exception\OperationException;

# Search for an entry object to get its current attributes / values
$entry = $ldap->read('cn=foo,dc=domain,dc=local');

# Add a value to an attribute
if (!$entry->get('telephoneNumber')) {
    $entry->add('telephoneNumber', '555-5555');
}
# Remove any values an attribute may have
if ($entry->has('title')) {
    $entry->reset('title');
}
# Delete a specific value for an attribute
if ($entry->get('ipPhone')->has('12345')) {
    $entry->delete('ipPhone', '12345');
}
# Set a value for an attribute. This replaces any value it may, or may not, have.
$entry->set('description', 'Employee');

# Send the built up changes back to LDAP to update the entry.
try {
    $ldap->update($entry);
} catch (OperationException $e) {
    echo sprintf('Error modifying entry (%s): %s', $e->getCode(), $e->getMessage());
}
```

## Rename Request

Rename an entry in LDAP, changing its RDN, and catch an operation exception:

```php
use FreeDSx\Entry\Rdn;
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Exception\OperationException;

# Rename an entry. Pass the DN as a string, or Dn object. Then pass an Rdn object or string RDN.
try {
    $ldap->send(Operations::rename('cn=foo,dc=domain,dc=local', new Rdn('cn', 'bar')));
} catch (OperationException $e) {
    echo sprintf('Error renaming entry (%s): %s', $e->getCode(), $e->getMessage());
}
```

## Move Request

Move an entry to a new OU/container/parent, and catch an operation exception:

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Exception\OperationException;

# Move an entry. First pass the DN to move, then the DN to move it to.
try {
    $ldap->send(Operations::move('cn=foo,dc=domain,dc=local', 'ou=workers,dc=domain,dc=local'));
} catch (OperationException $e) {
    echo sprintf('Error moving entry (%s): %s', $e->getCode(), $e->getMessage());
}
```

## Search Request

Perform a search for users to get the entries from LDAP:

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;

# Construct the LDAP search filter. Users whos last name starts with 'S'.
$filter = Filters::and(
    Filters::equal('objectClass', 'user'),
    Filters::startsWith('sn', 'S')
));

# Pass the filter to the search. Only grab the first name and last name attributes.
$entries = $ldap->search(Operations::search($filter, 'givenName', 'sn'));

foreach ($entries as $entry) {
    echo sprintf('%s => %s', $entry->get('givenName'), $entry->get('sn'));
}
```

Search for a single entry:

```php
use FreeDSx\Ldap\Operations;

# Pass search read a DN to query. Use the search() helper, grab the first entry...
$entry = $ldap->search(Operations::searchRead('cn=foo,dc=domain,dc=local'))->first();

if (!$entry) {
    echo "The LDAP entry was not found.";
} else {
    # Output the entry as an array of key => value pairs.
    var_dump($entry->toArray());
}
```

## Compare Request

Perform a simple equality check against an entry DN to see if it passes:

```php
# The comparison needs the DN (string or object) and an attribute name and value
if ($ldap->compare('cn=foo,dc=domain,dc=local', 'title', 'SysAdmin')) {
    echo "The DN '$dn' has a title containing SysAdmin!";
} else {
    echo "The DN '$dn' does not have a title that matches.";
}
```

## Password Modify Request

Perform an extended password modify operation:

```php
use FreeDSx\Ldap\Operations;

$dn = 'cn=foo,dc=domain,dc=local';
$oldPassword = 'FooB@r';
$newPassword = 'Super-Secret-Stuff!';

# Modify the password of the DN, supplying the old and new password.
# Requirements for this are directory specific. Leave a value null if not needed.
try {
    $ldap->send(Operations::passwordModify($dn, $oldPassword, $newPassword));
} catch (OperationException $e) {
    echo sprintf('Error modifying password (%s): %s', $e->getCode(), $e->getMessage());
}
```
