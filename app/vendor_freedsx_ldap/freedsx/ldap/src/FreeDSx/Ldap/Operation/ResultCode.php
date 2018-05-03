<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Operation;

/**
 * Message response result codes. Defined in RFC 4511, 4.1.9
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ResultCode
{
    public const SUCCESS = 0;

    public const OPERATIONS_ERROR = 1;

    public const PROTOCOL_ERROR = 2;

    public const TIME_LIMIT_EXCEEDED = 3;

    public const SIZE_LIMIT_EXCEEDED = 4;

    public const COMPARE_FALSE = 5;

    public const COMPARE_TRUE = 6;

    public const AUTH_METHOD_UNSUPPORTED = 7;

    public const STRONGER_AUTH_REQUIRED = 8;

    public const REFERRAL = 10;

    public const ADMIN_LIMIT_EXCEEDED = 11;

    public const UNAVAILABLE_CRITICAL_EXTENSION = 12;

    public const CONFIDENTIALITY_REQUIRED = 13;

    public const SASL_BIND_IN_PROGRESS = 14;

    public const NO_SUCH_ATTRIBUTE = 16;

    public const UNDEFINED_ATTRIBUTE_TYPE = 17;

    public const INAPPROPRIATE_MATCHING = 18;

    public const CONSTRAINT_VIOLATION = 19;

    public const ATTRIBUTE_OR_VALUE_EXISTS = 20;

    public const INVALID_ATTRIBUTE_SYNTAX = 21;

    public const NO_SUCH_OBJECT = 32;

    public const ALIAS_PROBLEM = 33;

    public const INVALID_DN_SYNTAX = 34;

    public const ALIAS_DEREFERENCING_PROBLEM = 36;

    public const INAPPROPRIATE_AUTHENTICATION = 48;

    public const INVALID_CREDENTIALS = 49;

    public const INSUFFICIENT_ACCESS_RIGHTS = 50;

    public const BUSY = 51;

    public const UNAVAILABLE = 52;

    public const UNWILLING_TO_PERFORM = 53;

    public const LOOP_DETECT = 54;

    public const SORT_CONTROL_MISSING = 60;

    public const OFFSET_RANGE_ERROR = 61;

    public const NAMING_VIOLATION = 64;

    public const OBJECT_CLASS_VIOLATION = 65;

    public const NOT_ALLOWED_ON_NON_LEAF = 66;

    public const NOT_ALLOWED_ON_RDN = 67;

    public const ENTRY_ALREADY_EXISTS = 68;

    public const OBJECT_CLASS_MODS_PROHIBITED = 69;

    public const AFFECTS_MULTIPLE_DSAS = 71;

    public const VIRTUAL_LIST_VIEW_ERROR = 76;

    public const OTHER = 80;

    public const CANCELED = 118;

    public const NO_SUCH_OPERATION = 119;

    public const TOO_LATE = 120;

    public const CANNOT_CANCEL = 121;

    public const ASSERTION_FAILED = 122;

    public const AUTHORIZATION_DENIED = 123;

    public const SYNCHRONIZATION_REFRESH_REQUIRED = 4096;
}
