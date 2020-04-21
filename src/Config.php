<?php

namespace Innocode\WPConfig;

use Dotenv\Dotenv;

/**
 * Class Config
 * @package Innocode\WPConfig
 */
final class Config
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var Dotenv
     */
    private $dotenv;
    /**
     * @var array
     */
    private $required_constants = [
        'DB_NAME',
        'DB_USER',
        'DB_PASSWORD',
        'AUTH_KEY',
        'SECURE_AUTH_KEY',
        'LOGGED_IN_KEY',
        'NONCE_KEY',
        'AUTH_SALT',
        'SECURE_AUTH_SALT',
        'LOGGED_IN_SALT',
        'NONCE_SALT',
    ];

    /**
     * Config constructor.
     * @param string $path
     */
    public function __construct( string $path )
    {
        $this->path = $path;
        $this->dotenv = Dotenv::create( $path );
    }

    /**
     * @param string $constant
     */
    public function add_required_constant( string $constant )
    {
        if ( ! $this->has_required_constant( $constant ) ) {
            $this->required_constants[] = $constant;
        }
    }

    /**
     * @param string $constant
     * @return bool
     */
    public function has_required_constant( string $constant ) : bool
    {
        return false === array_search( $constant, $this->required_constants );
    }

    public function init()
    {
        $this->dotenv->overload();
        $this->init_required_constants();
        $this->dotenv->required( $this->required_constants );
    }

    public function load()
    {
        foreach ( [
            'db',
            'secret-keys',
            'app',
            'debug',
            'mail',
        ] as $config ) {
            $this->require( $config );
        }

        if ( Helpers::is_multisite_allowed() ) {
            $this->require( 'multisite' );
        }

        if ( Helpers::is_s3_uploads_enabled() ) {
            $this->require( 's3-uploads' );
        }

        if ( Helpers::is_recaptcha_enabled() ) {
            $this->require( 'recaptcha' );
        }

        if ( Helpers::is_github_oauth_enabled() ) {
            $this->require( 'oauth' );
        }

        if ( Helpers::is_cdn_enabled() ) {
            $this->require( 'cdn' );
        }
    }

    public function get_config_by_name( string $name )
    {
        return "$this->path/config/$name.php";
    }

    /**
     * @param string $name
     */
    public function require( string $name )
    {
        $file = $this->get_config_by_name( $name );

        require_once $file;
    }

    private function init_required_constants()
    {
        if ( Helpers::is_multisite_allowed() && Helpers::is_multisite() ) {
            $this->required_constants[] = 'DOMAIN_CURRENT_SITE';
        } else {
            $this->required_constants[] = 'WP_HOME';
        }

        if ( Helpers::is_mailgun_enabled() ) {
            array_push(
                $this->required_constants,
                'MAILGUN_DOMAIN',
                'MAILGUN_FROM_ADDRESS'
            );
        }

        if ( Helpers::is_s3_uploads_enabled() ) {
            array_push(
                $this->required_constants,
                'S3_UPLOADS_KEY',
                'S3_UPLOADS_SECRET',
                'S3_UPLOADS_REGION'
            );
        }
    }
}
