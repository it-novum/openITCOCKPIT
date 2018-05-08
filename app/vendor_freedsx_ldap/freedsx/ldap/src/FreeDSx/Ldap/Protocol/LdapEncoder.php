<?php
/**
 * This file is part of the FreeDSx LDAP LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Protocol;

use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Type\AbstractType;

/**
 * Applies some LDAP specific rules, and mappings, to the BER encoder, specified in RFC 4511.
 *
 *    - Only the definite form of length encoding is used.
 *    - OCTET STRING values are encoded in the primitive form only.
 *    - If the value of a BOOLEAN type is true, the encoding of the value octet is set to hex "FF".
 *    - If a value of a type is its default value, it is absent.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class LdapEncoder extends BerEncoder
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct([
            'primitive_only' => [
                AbstractType::TAG_TYPE_OCTET_STRING,
            ],
        ]);
        $this->setTagMap(AbstractType::TAG_CLASS_APPLICATION, [
            0 => AbstractType::TAG_TYPE_SEQUENCE,
            1 => AbstractType::TAG_TYPE_SEQUENCE,
            2 => AbstractType::TAG_TYPE_NULL,
            3 => AbstractType::TAG_TYPE_SEQUENCE,
            4 => AbstractType::TAG_TYPE_SEQUENCE,
            5 => AbstractType::TAG_TYPE_SEQUENCE,
            6 => AbstractType::TAG_TYPE_SEQUENCE,
            7 => AbstractType::TAG_TYPE_SEQUENCE,
            8 => AbstractType::TAG_TYPE_SEQUENCE,
            9 => AbstractType::TAG_TYPE_SEQUENCE,
            10 => AbstractType::TAG_TYPE_OCTET_STRING,
            11 => AbstractType::TAG_TYPE_SEQUENCE,
            12 => AbstractType::TAG_TYPE_SEQUENCE,
            13 => AbstractType::TAG_TYPE_SEQUENCE,
            14 => AbstractType::TAG_TYPE_SEQUENCE,
            15 => AbstractType::TAG_TYPE_SEQUENCE,
            16 => AbstractType::TAG_TYPE_INTEGER,
            19 => AbstractType::TAG_TYPE_SEQUENCE,
            23 => AbstractType::TAG_TYPE_SEQUENCE,
            24 => AbstractType::TAG_TYPE_SEQUENCE,
            25 => AbstractType::TAG_TYPE_SEQUENCE,
        ]);
    }
}
