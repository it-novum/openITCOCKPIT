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

use FreeDSx\Asn1\Exception\PartialPduException;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Ldap\Exception\ConnectionException;
use FreeDSx\Ldap\Protocol\LdapEncoder;
use FreeDSx\Ldap\Protocol\LdapMessage;
use FreeDSx\Ldap\Protocol\LdapMessageRequest;
use FreeDSx\Ldap\Protocol\LdapMessageResponse;

/**
 * Used to retrieve message envelopes from the TCP stream.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
abstract class MessageQueue
{
    /**
     * @var Socket
     */
    protected $tcp;

    /**
     * @var LdapEncoder
     */
    protected $encoder;

    /**
     * @var false|string
     */
    protected $buffer = false;

    /**
     * @param Socket $tcp
     */
    public function __construct(Socket $tcp)
    {
        $this->tcp = $tcp;
        $this->encoder = new LdapEncoder();
    }

    /**
     * @param int|null $id
     * @return \Generator
     * @throws ConnectionException
     */
    public function getMessages(?int $id = null)
    {
        $this->buffer = ($this->buffer !== false) ? $this->buffer : $this->tcp->read();

        # Likely an unsolicited notification for a remote disconnect. For some reason, this forces it to be caught in
        # that case (but down below). This exception directly below is never thrown in that case. But the remote
        # disconnect message is never caught if this block is not here. Why???
        #
        # @todo PHP bug? Or logic issue with my generator use?
        if ($this->buffer === false) {
            throw new ConnectionException('The connection to the LDAP server has been lost.');
        }

        while ($this->buffer !== false) {
            $type = null;
            try {
                $type = $this->encoder->decode($this->buffer);
                $this->buffer = false;

                if ($type->getTrailingData() != '') {
                    $this->buffer = $type->getTrailingData();
                } elseif (($peek = $this->tcp->read(false)) !== false) {
                    $this->buffer .= $peek;
                }
            } catch (PartialPduException $e) {
                $this->buffer .= $this->tcp->read();
            }

            if ($type !== null) {
                yield $this->validate($this->constructPdu($type), $id);
            }
        }
    }

    /**
     * @param int|null $id
     * @return null|LdapMessage|LdapMessageResponse|LdapMessageRequest
     */
    public function getMessage(?int $id = null) : ?LdapMessage
    {
        return $this->getMessages($id)->current();
    }

    /**
     * Perform any needed validation against the PDU before it is returned.
     *
     * @param LdapMessage $message
     * @param int|null $id
     * @return LdapMessage
     */
    abstract protected function validate(LdapMessage $message, ?int $id = null) : LdapMessage;

    /**
     * Construct the PDU from ASN1.
     *
     * @param AbstractType $asn1
     * @return LdapMessage
     */
    abstract protected function constructPdu(AbstractType $asn1) : LdapMessage;
}
