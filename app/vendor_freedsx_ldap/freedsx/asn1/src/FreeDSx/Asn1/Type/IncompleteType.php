<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1\Type;

/**
 * Represents an incomplete ASN1 type where there was not enough information available to decode it. The value contains
 * the complete binary value.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class IncompleteType extends AbstractType
{
}
