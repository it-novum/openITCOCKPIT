<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Protocol;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\SequenceOfType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Control\ControlBag;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\Protocol\Factory\ControlFactory;
use FreeDSx\Ldap\Protocol\Factory\OperationFactory;

/**
 * The LDAP Message envelope (PDU). RFC 4511, 4.1.1
 *
 * LDAPMessage ::= SEQUENCE {
 *     messageID       MessageID,
 *     protocolOp      CHOICE {
 *         bindRequest           BindRequest,
 *         bindResponse          BindResponse,
 *         unbindRequest         UnbindRequest,
 *         searchRequest         SearchRequest,
 *         searchResEntry        SearchResultEntry,
 *         searchResDone         SearchResultDone,
 *         searchResRef          SearchResultReference,
 *         modifyRequest         ModifyRequest,
 *         modifyResponse        ModifyResponse,
 *         addRequest            AddRequest,
 *         addResponse           AddResponse,
 *         delRequest            DelRequest,
 *         delResponse           DelResponse,
 *         modDNRequest          ModifyDNRequest,
 *         modDNResponse         ModifyDNResponse,
 *         compareRequest        CompareRequest,
 *         compareResponse       CompareResponse,
 *         abandonRequest        AbandonRequest,
 *         extendedReq           ExtendedRequest,
 *         extendedResp          ExtendedResponse,
 *         ...,
 *         intermediateResponse  IntermediateResponse },
 *     controls       [0] Controls OPTIONAL }
 *
 * MessageID ::= INTEGER (0 ..  maxInt)
 *
 * maxInt INTEGER ::= 2147483647 -- (2^^31 - 1) --
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
abstract class LdapMessage implements ProtocolElementInterface
{
    /**
     * @var int
     */
    protected $messageId;

    /**
     * @var ControlBag
     */
    protected $controls;

    /**
     * @param int $messageId
     * @param Control[] ...$controls
     */
    public function __construct(int $messageId, Control ...$controls)
    {
        $this->messageId = $messageId;
        $this->controls = new ControlBag(...$controls);
    }

    /**
     * @return int
     */
    public function getMessageId() : int
    {
        return $this->messageId;
    }

    /**
     * Get the controls for this specific message.
     *
     * @return ControlBag
     */
    public function controls() : ControlBag
    {
        return $this->controls;
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1() : AbstractType
    {
        $asn1 = Asn1::sequence(
            Asn1::integer($this->messageId),
            $this->getOperationAsn1()
        );

        if (!empty($this->controls->toArray())) {
            /** @var SequenceOfType $controls */
            $controls = Asn1::context(0, Asn1::sequenceOf());
            foreach ($this->controls->toArray() as $control) {
                $controls->addChild($control->toAsn1());
            }
            $asn1->addChild($controls);
        }

        return $asn1;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        self::validateAsn1($type);
        $controls = [];

        /** @var SequenceType $type */
        foreach ($type->getChildren() as $child) {
            if ($child->getTagClass() === AbstractType::TAG_CLASS_CONTEXT_SPECIFIC && $child->getTagNumber() === 0) {
                /** @var \FreeDSx\Asn1\Type\IncompleteType $child */
                $child = (new LdapEncoder())->complete($child, AbstractType::TAG_TYPE_SEQUENCE);
                /** @var SequenceOfType $child */
                foreach ($child->getChildren() as $control) {
                    $controls[] = ControlFactory::get($control);
                }
            }
        }

        return new static(
            $type->getChild(0)->getValue(),
            OperationFactory::get($type->getChild(1)),
            ...$controls
        );
    }

    /**
     * @return AbstractType
     */
    abstract protected function getOperationAsn1() : AbstractType;

    /**
     * @param AbstractType $type
     * @throws ProtocolException
     */
    protected static function validateAsn1(AbstractType $type)
    {
        if (!$type instanceof SequenceType) {
            throw new ProtocolException(sprintf(
                'Expected an ASN1 sequence type, but got: %s',
                get_class($type)
            ));
        }
        if (count($type->getChildren()) < 2) {
            throw new ProtocolException(sprintf(
                'Expected an ASN1 sequence with at least 2 elements, but it has %s',
                count($type->getChildren())
            ));
        }
        if (!$type->getChild(0) instanceof IntegerType) {
            throw new ProtocolException(sprintf(
                'Expected an LDAP message ID, but got: %s',
                get_class($type->getChild(0))
            ));
        }
    }
}
