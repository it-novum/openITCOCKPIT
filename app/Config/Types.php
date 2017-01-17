<?php

class Types
{
    const CODE_SUCCESS = 'success';
    const CODE_ERROR = 'error';
    const CODE_EXCEPTION = 'exception';
    const CODE_MISSING_PARAMETERS = 'missing_parameters';
    const CODE_NOT_AUTHENTICATED = 'not_authenticated';
    const CODE_AUTHENTICATION_FAILED = 'authentication_failed';
    const CODE_VALIDATION_FAILED = 'validation_failed';
    const CODE_NOT_ALLOWED = 'not_allowed';
    const CODE_NOT_AVAILABLE = 'not_available';
    const CODE_INVALID_TRIGGER_ACTION_ID = 'invalid_trigger_action_id';

    const ROLE_ADMIN = 'admin';
    const ROLE_EMPLOYEE = 'employee';

    public static $description = [
        self::ROLE_ADMIN    => 'Admin',
        self::ROLE_EMPLOYEE => 'Employee',
    ];

    public static function getMap($types = null)
    {
        if (!is_array($types)) {
            $types = func_get_args();
        }
        $map = [];
        if ($types == null) $types = array_keys(self::$description);
        foreach ($types as $type) {
            $map[$type] = __(self::$description[$type]);
        }

        return $map;
    }

    public static function getDescription($type)
    {
        if (isset(self::$description[$type])) {
            return __(self::$description[$type]);
        }

        return null;
    }
}