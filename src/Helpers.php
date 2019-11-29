<?php

namespace Innocode\WPConfig;

/**
 * Class Helpers
 * @package Innocode\WPConfig
 */
final class Helpers
{
    public static function ssl_fix()
    {
        if (
            isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
        ) {
            $_SERVER['HTTPS'] = 'on';
        }
    }

    /**
     * @return bool
     */
    public static function is_s3_uploads_enabled() : bool
    {
        return (bool) Env::get( 'S3_UPLOADS_BUCKET' );
    }

    /**
     * @return bool
     */
    public static function is_multisite_allowed() : bool
    {
        return Env::get( 'WP_ALLOW_MULTISITE' );
    }

    /**
     * @return bool
     */
    public static function is_multisite() : bool
    {
        return Env::get( 'MULTISITE' );
    }

    /**
     * @return bool
     */
    public static function is_recaptcha_enabled() : bool
    {
        return (bool) Env::get( 'RECAPTCHA_KEY' );
    }

    /**
     * @return bool
     */
    public static function is_github_oauth_enabled() : bool
    {
        return (bool) Env::get( 'GITHUB_OAUTH_CLIENT_ID' );
    }

    /**
     * @return bool
     */
    public static function is_cdn_enabled() : bool
    {
        return (bool) Env::get( 'CDN' );
    }
}
