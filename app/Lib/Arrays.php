<?php


class Arrays {
    public static function ensureArrayKeys($src, $target) {
        foreach ($target as $key => $value) {
            if (!array_key_exists($key, $src)) {
                $src[$key] = $value;
            } else if (is_array($value) && is_array($src[$key])) {
                $src[$key] = self::ensureArrayKeys($src[$key], $value);
            }
        }

        return $src;
    }
}
