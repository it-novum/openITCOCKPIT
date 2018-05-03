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
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Protocol\LdapMessage;

/**
 * RFC 3909. A request to cancel an operation.
 *
 * cancelRequestValue ::= SEQUENCE {
 *     cancelID        MessageID
 *     -- MessageID is as defined in [RFC2251]
 * }
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class CancelRequest extends ExtendedRequest
{
    /**
     * @var int
     */
    protected $messageId;

    /**
     * @param int|LdapMessage $messageId
     */
    public function __construct($messageId)
    {
        $this->setMessageId($messageId);
        parent::__construct(self::OID_CANCEL);
    }

    /**
     * @return int
     */
    public function getMessageId() : int
    {
        return $this->messageId;
    }

    /**
     * @param int|LdapMessage $messageId
     * @return $this
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId instanceof LdapMessage ? $messageId->getMessageId() : (int) $messageId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        $this->requestValue = Asn1::sequence(Asn1::integer($this->messageId));

        return parent::toAsn1();
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $value = self::decodeEncodedValue($type);
        if (!($value instanceof SequenceType && $value->getChild(0) instanceof IntegerType)) {
            throw new ProtocolException('The cancel request value is malformed.');
        }

        return new self($value->getChild(0)->getValue());
    }
}
