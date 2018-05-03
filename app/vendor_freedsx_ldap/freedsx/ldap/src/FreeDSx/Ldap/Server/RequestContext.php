<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Server;

use FreeDSx\Ldap\Control\ControlBag;
use FreeDSx\Ldap\Server\Token\TokenInterface;

/**
 * Represents the context of a server request. This includes any controls associated with the request and the token for
 * authentication details.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class RequestContext
{
    /**
     * @var ControlBag
     */
    protected $controls;

    /**
     * @var TokenInterface
     */
    protected $token;

    /**
     * @param ControlBag $controls
     * @param TokenInterface $token
     */
    public function __construct(ControlBag $controls, TokenInterface $token)
    {
        $this->controls = $controls;
        $this->token = $token;
    }

    /**
     * @return ControlBag
     */
    public function controls()
    {
        return $this->controls;
    }

    /**
     * @return TokenInterface
     */
    public function token()
    {
        return $this->token;
    }
}
