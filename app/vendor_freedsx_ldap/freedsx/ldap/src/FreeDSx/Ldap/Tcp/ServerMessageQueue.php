<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Tcp;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Ldap\Protocol\LdapMessage;
use FreeDSx\Ldap\Protocol\LdapMessageRequest;

/**
 * Used by the server to retrieve message requests from the client.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ServerMessageQueue extends MessageQueue
{
    /**
     * {@inheritdoc}
     */
    protected function constructPdu(AbstractType $asn1): LdapMessage
    {
        return LdapMessageRequest::fromAsn1($asn1);
    }

    /**
     * {@inheritdoc}
     */
    protected function validate(LdapMessage $message, ?int $id = null): LdapMessage
    {
        return $message;
    }
}
