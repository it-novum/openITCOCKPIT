<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Operation\Request;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\NullType;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * A request to unbind. RFC 4511, 4.3
 *
 * UnbindRequest ::= [APPLICATION 2] NULL
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class UnbindRequest implements RequestInterface
{
    protected const APP_TAG = 2;

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        if (!$type instanceof NullType) {
            throw new ProtocolException('The unbind request is invalid');
        }

        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        return Asn1::application(self::APP_TAG, Asn1::null());
    }
}
