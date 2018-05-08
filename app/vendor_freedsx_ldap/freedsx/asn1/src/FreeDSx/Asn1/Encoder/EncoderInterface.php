<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1\Encoder;

use FreeDSx\Asn1\Exception\EncoderException;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IncompleteType;

/**
 * The ASN1 encoder interface.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
interface EncoderInterface
{
    /**
     * Encode a type to its binary form.
     *
     * @param AbstractType $type
     * @return string
     * @throws EncoderException
     */
    public function encode(AbstractType $type) : string;

    /**
     * Decodes (completes) an incomplete type to a specific universal tag type object.
     *
     * @param IncompleteType $type
     * @param int $tagType
     * @param array $tagMap
     * @return mixed
     * @throws EncoderException
     */
    public function complete(IncompleteType $type, int $tagType, array $tagMap = []) : AbstractType;

    /**
     * Decode binary data to its ASN1 object representation.
     *
     * @param string $binary
     * @param array $tagMap
     * @return AbstractType
     * @throws EncoderException
     */
    public function decode($binary, array $tagMap = []) : AbstractType;
}
