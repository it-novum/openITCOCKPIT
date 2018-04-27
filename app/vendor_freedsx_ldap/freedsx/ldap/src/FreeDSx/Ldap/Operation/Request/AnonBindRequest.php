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

/**
 * Represents an anonymous bind request.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class AnonBindRequest extends BindRequest
{
    /**
     * @param string $username
     * @param int $version
     */
    public function __construct(string $username = '', int $version = 3)
    {
        $this->username = $username;
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAsn1AuthChoice(): AbstractType
    {
        return Asn1::context(0, Asn1::octetString(''));
    }

    /**
     * {@inheritdoc}
     */
    protected function validate(): void
    {
    }
}
