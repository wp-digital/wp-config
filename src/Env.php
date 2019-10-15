<?php

namespace Innocode\WPConfig;

/**
 * Class Env
 * @package Innocode\WPConfig
 */
class Env
{
    /**
     * @param string $name
     * @param null   $default
     * @return array|bool|false|string|null
     */
    public static function get( string $name, $default = null )
    {
        $value = getenv( $name );

        switch ( $value ) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
                return null;
        }

        if ( false === $value && ! is_null( $default ) ) {
            return $default;
        }

        return $value;
    }
}
