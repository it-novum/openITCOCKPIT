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
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * RFC 4511, 4.11.
 *
 * AbandonRequest ::= [APPLICATION 16] MessageID
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class AbandonRequest implements RequestInterface
{
    protected const APP_TAG = 16;

    /**
     * @var int
     */
    protected $messageID;

    /**
     * @param int $messageID
     */
    public function __construct(int $messageID)
    {
        $this->messageID = $messageID;
    }

    /**
     * @param int $messageID
     * @return $this
     */
    public function setMessageId(int $messageID)
    {
        $this->messageID = $messageID;

        return $this;
    }

    /**
     * @return int
     */
    public function getMessageId() : int
    {
        return $this->messageID;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        if (!$type instanceof IntegerType) {
            throw new ProtocolException('The abandon request is malformed');
        }

        return new self($type->getValue());
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        return Asn1::application(self::APP_TAG, Asn1::integer($this->messageID));
    }
}
