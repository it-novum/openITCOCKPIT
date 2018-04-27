# Searching and Filters

* [Standard Searches](#standard-searches)
  * [Read Search](#read-search)
  * [List Search](#list-search)
  * [Subtree Search](#subtree-search)
* [Sorting](#sorting)
* [Paging](#paging)
* [VLV](#vlv)
* [Filters](#filters)
  * [Construction Using Objects](#construction-using-objects)
    * [And](#and)
    * [Or](#or)
    * [Negation](#negation)
    * [Equality](#equality)
    * [Approximate](#approximate)
    * [Starts With](#starts-with)
    * [Ends With](#ends-with)
    * [Contains](#contains)
    * [Present](#present)
    * [Greater Than or Equal](#greater-than-or-equal)
    * [Less Than or Equal](#less-than-or-equal)
  * [Construction Using String Filters](#construction-using-string-filters)
  * [String Representation](#string-representation)

To search LDAP you can use standard searching, paging, or VLV. Each of these has their own use. The client class has 
helper methods for making each of these easier to work with.
 
## Standard Searches

There are three standard search types when constructing your search request: subtree, list, and read. Each of these search
types should be passed to the `search()` method of the LDAP client. The search method is a shorthand way to send a search
request and have the entries returned immediately.

### Subtree Search

Search LDAP from the base DN on downwards:

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;

# Construct the LDAP search filter. Users whose title contains 'Administrator'.
$filter = Filters::and(
    Filters::equal('objectClass', 'user'),
    Filters::contains('title', 'Administrator')
));

# Pass the filter to the search. Only grab the 'cn' attribute.
$entries = $ldap->search(Operations::search($filter, 'cn'));

foreach ($entries as $entry) {
    echo "cn: ".$entry->get('cn').PHP_EOL;
}
```

### List Search

The list search shows the immediate children of the base DN (like a non-recursive directory listing).

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;

# Only look for OUs
$filter = Filters::equal('objectClass', 'organizationalUnit')

# Grab all entries at the root, select only the 'ou' attribute
$entries = $ldap->search(Operations::list($filter, 'dc=domain,dc=local', 'ou'));

foreach ($entries as $entry) {
    echo "ou: ".$entry->get('ou').PHP_EOL;
}
```

### Read Search

The read search gets the entry defined by the base DN. It will only return one entry (if it exists).

```php
use FreeDSx\Ldap\Operations;

# Use the read() method of the LDAP client to search for a specific entry.
$entry = $ldap->read('cn=foo,dc=domain,dc=local');

# Entry will be null if it doesn't exist
if ($entry) {
    echo $entry->getDn().PHP_EOL;
    var_dump($entry->toArray());
}
```

## Sorting

You can sort searches by a specific attribute, or set of attributes, and a direction by using a server side sort control.
This control will sort the results in ascending order by default:

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;
use FreeDSx\Ldap\Controls;

$filter = Filters::and(Filters::equal('objectClass', 'user'), Filters::present('sn')));

# Sort the results by last name
$entries = $ldap->search(Operations::search($filter, 'sn'), Controls::sort('sn'));

foreach ($entries as $entry) {
    echo "sn: ".$entry->get('sn').PHP_EOL;
}
```

Sort by last name in descending order by using a sort key object:

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;
use FreeDSx\Ldap\Controls;
use FreeDSx\Ldap\Control\Sorting\SortKey;

$filter = Filters::and(Filters::equal('objectClass', 'user'), Filters::present('sn')));

# Sort the results by last name in descending order
$sortKey = SortKey::descending('sn');
$entries = $ldap->search(Operations::search($filter, 'sn'), Controls::sort($sortKey));

foreach ($entries as $entry) {
    echo "sn: ".$entry->get('sn').PHP_EOL;
}
```

## Paging

A paging search retrieves a certain subset of the search results at a time. This allows you to go through the results in
smaller chunks, then move on to the next. Also, some LDAP servers may limit search results to a certain number of entries.
This may make it necessary to use paging for some searches to retrieve all of the results of a search.

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;

# Create a search operation to be used in paging 
$search = Operations::search(Filters::equal('objectClass', 'user'), 'cn');

# Create a paged search using the client 'paging()' method, 100 results at a time
$paging = $ldap->paging($search, 100);

# Loop through the paged results until we reach the end
while ($paging->hasEntries()) {
    $entries = $paging->getEntries();
    var_dump(count($entries));
    
    foreach ($entries as $entry) {
        echo "Entry: ".$entry->getDn().PHP_EOL;
    }
}
```

At any time during the paging operation you can call the `$paging->end()` operation and break the loop. This will
gracefully abort the paged search.

## VLV

A VLV (Virtual List View) search can be used to retrieve a certain subset of a search result. Such as starting from a
specific entry index, or starting at a certain percentage of the overall result set. This sort of search could be useful
in a GUI using a percentage slider to move around a large result set with a specified offset of results to show at any
given time.

```php
use FreeDSx\Ldap\Operations;
use FreeDSx\Ldap\Search\Filters;

# Create a search operation
$search = Operations::search(Filters::equal('objectClass', 'user'), 'cn');

# Create a VLV search request. The second parameter (required) is the attribute to sort by.
# The final paramter is how many entries to retrieve past the starting point.
$vlv = $client->vlv($search, 'cn', 100);

# Use percentages, start at the midway point instead of the beginning
$vlv->asPercentage()->startAt(50);

# Get a set of 100 entries starting at the halfway mark
$entries = $vlv->getEntries();

echo "Entries: ".count($entries).PHP_EOL;
foreach ($entries as $entry) {
    echo $entry->getDn().PHP_EOL;
}

# Move forward 25%, putting us at the 75% mark, get the entries there.
$entries = $vlv->moveForward(25)->getEntries();

echo "Entries: ".count($entries).PHP_EOL;
foreach ($entries as $entry) {
    echo $entry->getDn().PHP_EOL;
}

echo sprintf('At offset %s out of %s.', $vlv->listOffset(), $vlv->listSize()).PHP_EOL;
```

## Filters

### Construction Using Objects

All filters can be constructed using the factory methods available on `FreeDSx\Ldap\Search\Filters`. The filter object is
then passed on to the search request.

------------------
#### And

Generates a logical 'and' statement by adding multiple filters together.

```php
use FreeDSx\Ldap\Search\Filters;

# All of these added filters must pass for the entry to be returned.
$filter = Filters::and(
    # An entry with an objectClass of user
    Filters::equal('objectClass', 'user'),
    # Must have a non-empty telephoneNumber
    Filters::present('telephoneNumber'),
    # Entries without an email address value
    Filters::not(Filters::present('emailAddress'))
};
```

------------------
#### Or

Generates a logical 'or' statement by adding multiple filters together.

```php
use FreeDSx\Ldap\Search\Filters;

# If any of these added filters pass the entry will be returned
$filter = Filters::or(
    # Get user objects
    Filters::equal('objectClass', 'user'),
    # Get group objects
    Filters::equal('objectClass', 'group'),
};
```

------------------
#### Negation

Negate a filter by wrapping it in a 'not' statement.

```php
use FreeDSx\Ldap\Search\Filters;

# Only entries that do not have a title that contains 'Manager'
$filter = Filters::not(Filters::contains('title', 'Manager'));
```

------------------
#### Equality

Checks that an attribute has a specific value.

```php
use FreeDSx\Ldap\Search\Filters;

# Only Bobs
$filter = Filters::equal('givenName', 'Bob');
```

------------------
#### Approximate

Checks that an attribute value matches the approximate matching algorithm used by the LDAP (phonetic, spelling variations,
etc). This is LDAP server specific. If the server does not support an approximate match it will be treated as an equality
check.  

```php
use FreeDSx\Ldap\Search\Filters;

# Only Bobs
$filter = Filters::approximate('givenName', 'Jon');
```

------------------
#### Starts With

Checks that an attribute value starts with something specific.

```php
use FreeDSx\Ldap\Search\Filters;

# Only phone numbers starting with 608
$filter = Filters::startsWith('telephoneNumber', '608');
```

#### Ends With

Checks that an attribute value ends with something specific.

```php
use FreeDSx\Ldap\Search\Filters;

# Only office names that end in 'Michigan'
$filter = Filters::endsWith('physicalDeliveryOfficeName', 'Michigan');
```

------------------
#### Contains

Checks that an attribute value contains something specific.

```php
use FreeDSx\Ldap\Search\Filters;

# Only entries with titles containing 'admin'
$filter = Filters::contains('title', 'admin');
```

------------------
#### Present

Checks if there is anything value present/set on a specific attribute.

```php
use FreeDSx\Ldap\Search\Filters;

# Only entries with a telephone number present
$filter = Filters::present('telephoneNumber');
```

------------------
#### Greater Than or Equal

Checks if an attribute value is greater than or equal to a specific value.

```php
use FreeDSx\Ldap\Search\Filters;

# Check for entries where the creation date is greater than or equal to a timestamp (AD specific attribute)
$filter = Filters::gte('whenCreated', '20170101000000.0Z');
```

------------------
#### Less Than or Equal

Checks if an attribute value is less than or equal to a specific value.

```php
use FreeDSx\Ldap\Search\Filters;

# Check for entries where the creation date is less than or equal to a timestamp (AD specific attribute)
$filter = Filters::lte('whenCreated', '20170101000000.0Z');
```
    
### Construction Using String Filters

Instead of using the filter object construction detailed above, you can also construct a filter object from a raw filter
string. However, you should not blindly accept user entered filters depending on what it is used for, as it opens you
up to possible LDAP filter injection from values that are not escaped properly.

The `raw($filter)` method can be used to create filter objects based on a string filter:

```php
# Produces an 'and' filter object containing the rest of the logic in the filter string.
$filter = Filters::raw('(&(objectClass=user)(title=*Manager*)(|(st=Wisconsin)(st=Illinois)))');
```

### String Representation

You can also dump the filter objects back to string form to save it off and use later:

```php
# Get the filter object
$filter = Filters::raw('(&(objectClass=user)(title=*Manager*)(|(st=Wisconsin)(st=Illinois)))');

$filterString = $filter->toString();

# This will output '(&(objectClass=user)(title=*Manager*)(|(st=Wisconsin)(st=Illinois)))'
echo $filterString;
```
