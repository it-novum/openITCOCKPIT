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
 * Used between the Set type and Encoder.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
trait SetTrait
{
    /**
     * X.680 Sec 8.4. A set is canonical when:
     *    - Universal classes first.
     *    - Application classes second.
     *    - Context specific classes third.
     *    - Private classes last.
     *    - Within each group of classes above, tag numbers should be ordered in ascending order.
     *
     * @param AbstractType[] ...$set
     * @return AbstractType[]
     */
    protected function canonicalize(AbstractType ...$set) : array
    {
        $children = [
            AbstractType::TAG_CLASS_UNIVERSAL => [],
            AbstractType::TAG_CLASS_APPLICATION => [],
            AbstractType::TAG_CLASS_CONTEXT_SPECIFIC => [],
            AbstractType::TAG_CLASS_PRIVATE => [],
        ];

        # Group them by their respective class type.
        foreach ($set as $child) {
            $children[$child->getTagClass()][] = $child;
        }

        # Sort the classes by tag number.
        foreach ($children as $class => $type) {
            usort($children[$class], function ($a, $b) {
                /* @var AbstractType $a
                 * @var AbstractType $b */
                return ($a->getTagNumber() < $b->getTagNumber()) ? -1 : 1;
            });
        }

        return array_merge(
            $children[AbstractType::TAG_CLASS_UNIVERSAL],
            $children[AbstractType::TAG_CLASS_APPLICATION],
            $children[AbstractType::TAG_CLASS_CONTEXT_SPECIFIC],
            $children[AbstractType::TAG_CLASS_PRIVATE]
        );
    }
}
