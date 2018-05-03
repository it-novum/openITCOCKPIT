<?php
/**
 * This file is part of the FreeDSx ASN1 package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Asn1;

use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Encoder\DerEncoder;

/**
 * Simple factory methods for easily getting an encoder instance for encoding / decoding.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Encoders
{
    /**
     * @param array $options
     * @return BerEncoder
     */
    public static function ber(array $options = []) : BerEncoder
    {
        return new BerEncoder($options);
    }

    /**
     * @param array $options
     * @return DerEncoder
     */
    public static function der(array $options = []) : DerEncoder
    {
        return new DerEncoder($options);
    }
}
