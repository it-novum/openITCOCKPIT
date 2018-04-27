<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Exception;

/**
 * Thrown when an unsolicited notification is received. Holds the error, code, and OID of the notification type.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class UnsolicitedNotificationException extends ProtocolException
{
    /**
     * @var string
     */
    protected $oid;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param string $oid
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null, $oid = "")
    {
        $this->oid = $oid;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the name OID identifying the unsolicited notification.
     *
     * @return string
     */
    public function getOid()
    {
        return $this->oid;
    }
}
