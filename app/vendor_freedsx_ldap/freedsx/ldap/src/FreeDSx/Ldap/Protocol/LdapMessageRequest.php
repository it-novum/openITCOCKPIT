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

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Operation\Request\RequestInterface;

/**
 * The LDAP Message envelope PDU. This represents a message as a request to LDAP.
 *
 * @see LdapMessage
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class LdapMessageRequest extends LdapMessage
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param int $messageId
     * @param RequestInterface $request
     * @param Control[] ...$controls
     */
    public function __construct(int $messageId, RequestInterface $request, Control ...$controls)
    {
        $this->request = $request;
        parent::__construct($messageId, ...$controls);
    }

    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }

    /**
     * @return AbstractType
     */
    protected function getOperationAsn1(): AbstractType
    {
        return $this->request->toAsn1();
    }
}
