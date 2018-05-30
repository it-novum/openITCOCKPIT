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
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Operation\Response\ExtendedResponse;
use FreeDSx\Ldap\Protocol\LdapMessage;
use FreeDSx\Ldap\Protocol\LdapMessageResponse;
use FreeDSx\Ldap\Exception\UnsolicitedNotificationException;

/**
 * Used by the client to retrieve message from the server.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ClientMessageQueue extends MessageQueue
{
    /**
     * {@inheritdoc}
     */
    protected function constructPdu(AbstractType $asn1): LdapMessage
    {
        return LdapMessageResponse::fromAsn1($asn1);
    }

    /**
     * Checks for two separate things:
     *
     *  - Unsolicited notification messages, which is a message with an ID of zero and an ExtendedResponse type.
     *  - Unexpected message ID responses if we expect a specific message ID to be returned.
     *
     * @param LdapMessage $message
     * @param int|null $id
     * @return LdapMessage
     * @throws ProtocolException
     * @throws UnsolicitedNotificationException
     */
    protected function validate(LdapMessage $message, ?int $id = null) : LdapMessage
    {
        /** @var LdapMessageResponse $message */
        if ($message->getMessageId() === 0 && $message->getResponse() instanceof ExtendedResponse) {
            /** @var ExtendedResponse $response */
            $response = $message->getResponse();
            throw new UnsolicitedNotificationException(
                $response->getDiagnosticMessage(),
                $response->getResultCode(),
                null,
                $response->getName()
            );
        }
        if ($id !== null && $id !== $message->getMessageId()) {
            throw new ProtocolException(sprintf(
                'Expected a LDAP PDU with an ID %s, but received %s.',
                $id,
                $message->getMessageId()
            ));
        }

        return $message;
    }
}
