<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1\Exception;

/**
 * Thrown when the encoder determines it doesn't have the full length of data to construct the PDU. If we don't have
 * enough data in a non-PDU ASN1 element, then an Encoder Exception will be thrown instead.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class PartialPduException extends EncoderException
{
}
