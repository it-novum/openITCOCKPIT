<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

namespace App\Lib\Traits;

/**
 * Add Bit-Flags support to an Entity
 * Requires the Entity class to define self::FLAG_BLANK as constant.
 */
trait BitFlagTrait {

    /**
     * Add a new flag to the entity object
     * See self::FLAG_ constants for a list of available flags
     *
     * @param int $flag
     * @return bool
     * @throws \Exception
     */
    public function addFlag(int $flag): bool {
        if ($this->isNew()) {
            // New entities does not have any flags
            $this->set('flags', $flag);
        }

        $flags = $this->flags;

        if ($flags === null) {
            throw new \Exception('Entity was fetched without flags field!');
        }

        if ($flag & $flags) {
            // Entity already has the given flag
            return true;
        } else {
            // Add new flag to entity flags
            $this->set('flags', ($flags + $flag));
        }

        return true;
    }


    /**
     * Remove a new flag to the entity object
     * See self::FLAG_ constants for a list of available flags
     *
     * @param int $flag
     * @return true
     * @throws \Exception
     */
    public function removeFlag(int $flag): bool {
        $flags = $this->flags;

        if ($flags === null) {
            throw new \Exception('Entity was fetched without flags field!');
        }

        if ($flags & $flag) {
            // Remove flag from Entity
            $flags = $flags - $flag;

            if ($flags < 0) {
                $flags = self::FLAG_BLANK;
            }

            $this->set('flags', $flags);
        }
        return true;
    }

    /**
     * Checks if a flag is set to the entity
     *
     * @param int $flag
     * @return bool
     * @throws \Exception
     */
    public function hasFlag(int $flag): bool {
        if ($this->flags === null) {
            throw new \Exception('Entity was fetched without flags field!');
        }

        return ($this->flags & $flag) > 0;
    }

    /**
     * Remove ALL flags from the entity
     *
     * @return true
     * @throws \Exception
     */
    public function removeAllFlags(): bool {
        if ($this->flags === null) {
            throw new \Exception('Entity was fetched without flags field!');
        }

        $this->set('flags', self::FLAG_BLANK);
        return true;
    }

}
