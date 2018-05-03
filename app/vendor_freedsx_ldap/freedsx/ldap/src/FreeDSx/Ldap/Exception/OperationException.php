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

use FreeDSx\Ldap\Operation\ResultCode;

/**
 * Used in client-side requests to indicate generic issues with non-success request responses for operations. Used to
 * indicate an error during server-side operation processing. The resulting message and code is used in the
 * LDAP result sent back to the client (when thrown from the request handler).
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class OperationException extends \Exception
{
    public function __construct($message = "", $code = ResultCode::OPERATIONS_ERROR, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
