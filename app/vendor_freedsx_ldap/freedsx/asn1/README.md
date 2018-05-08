# FreeDSx ASN1 [![Build Status](https://travis-ci.org/FreeDSx/ASN1.svg?branch=master)](https://travis-ci.org/FreeDSx/ASN1) [![AppVeyor Build Status](https://ci.appveyor.com/api/projects/status/github/freedsx/asn1?branch=master&svg=true)](https://ci.appveyor.com/project/ChadSikorra/asn1)
FreeDSx ASN1 is a PHP library for dealing with ASN.1 data structures. Its original focus was on ASN.1 BER encoding used in
LDAP as part of the FreeDSx LDAP library. It was moved to its own library to allow for additional encoders and reuse in
other projects.

# Getting Started

Install via composer:

```bash
composer require freedsx/asn1
```

## Encoding

To encode an ASN.1 structure you can use the helper methods of the Asn1 class and an encoder:

```php
use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Encoders;

# Create the ASN.1 structure you need...
$asn1 = Asn1::sequence(
    Asn1::integer(9999),
    Asn1::octetString('foo'),
    Asn1::boolean(true)
);

# Encoded $bytes will now contain the BER binary representation of a sequence containing:
#  - An integer type of value 9999
#  - An octet string type of value 'foo'
#  - A boolean type of true
$bytes = Encoders::ber()->encode($asn1);

# Encode using the more strict DER encoder
$bytes = Encoders::der()->encode($asn1);
```

## Decoding

To decode an ASN.1 structure you can get an encoder and call decode then parse it out:

```php
use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Encoders;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\BooleanType;

# Assuming bytes contains the binary BER encoded sequence described in the encoding section
# Get a BER encoder instance, call decode on it, and $pdu will now be a sequence object.
$pdu = Encoders::ber()->decode($bytes);

# You could also decode using DER, if that's what you're expecting...
$pdu = Encoders::der()->decode($bytes);

# Validate the structure you are expecting...
if (!($pdu instanceof SequenceType && count($pdu) === 3)) {
    echo "Decoded structure is invalid.".PHP_EOL;
    exit;
}

# Loop through the sequence and check the individual types it contains...
foreach ($pdu as $i => $type) {
    if ($i === 0 && $type instanceof IntegerType) {
        var_dump($type->getValue());
    } elseif ($i === 1 && $type instanceof OctetStringType) {
        var_dump($type->getValue());
    } elseif ($i === 2 && $type instanceof BooleanType) {
        var_dump($type->getValue());
    } else {
        echo "Invalid type encountered.".PHP_EOL;
        exit;
    }
}
```
