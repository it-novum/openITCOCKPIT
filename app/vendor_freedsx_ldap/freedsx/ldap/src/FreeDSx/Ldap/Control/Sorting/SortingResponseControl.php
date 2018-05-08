<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Control\Sorting;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\SequenceType;
use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Exception\ProtocolException;

/**
 * A Server Side Sorting response control value. RFC 2891.
 *
 * SortResult ::= SEQUENCE {
 *     sortResult  ENUMERATED {
 *         success                   (0), -- results are sorted
 *         operationsError           (1), -- server internal failure
 *         timeLimitExceeded         (3), -- timelimit reached before
 *         -- sorting was completed
 *         strongAuthRequired        (8), -- refused to return sorted
 *                                        -- results via insecure
 *                                        -- protocol
 *         adminLimitExceeded       (11), -- too many matching entries
 *                                        -- for the server to sort
 *         noSuchAttribute          (16), -- unrecognized attribute
 *                                        -- type in sort key
 *         inappropriateMatching    (18), -- unrecognized or
 *                                        -- inappropriate matching
 *                                        -- rule in sort key
 *         insufficientAccessRights (50), -- refused to return sorted
 *                                        -- results to this client
 *         busy                     (51), -- too busy to process
 *         unwillingToPerform       (53), -- unable to sort
 *         other                    (80)
 *         },
 *     attributeType [0] AttributeDescription OPTIONAL }
 *
 *  @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class SortingResponseControl extends Control
{
    /**
     * @var int
     */
    protected $result;

    /**
     * @var null|string
     */
    protected $attribute;

    /**
     * @param int $result
     * @param null|string $attribute
     */
    public function __construct(int $result, ?string $attribute = null)
    {
        $this->result = $result;
        $this->attribute = $attribute;
        parent::__construct(self::OID_SORTING_RESPONSE);
    }

    /**
     * @return int
     */
    public function getResult() : int
    {
        return $this->result;
    }

    /**
     * @return null|string
     */
    public function getAttribute() : ?string
    {
        return $this->attribute;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        $sorting = parent::decodeEncodedValue($type);
        if (!$sorting instanceof SequenceType) {
            throw new ProtocolException('The server side sorting response is malformed.');
        }

        $response = new self(
            $sorting->getChild(0)->getValue(),
            $sorting->hasChild(1) ? $sorting->getChild(1)->getValue() : null
        );

        return parent::mergeControlData($response, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function toAsn1(): AbstractType
    {
        $this->controlValue = Asn1::sequence(Asn1::enumerated($this->result));
        if ($this->attribute !== null) {
            $this->controlValue->addChild(Asn1::context(0, Asn1::octetString($this->attribute)));
        }

        return parent::toAsn1();
    }
}
