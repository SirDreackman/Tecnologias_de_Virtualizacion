<?php
define( 'DB_NAME', 'wp' );
define( 'DB_USER', 'admin' );
define( 'DB_PASSWORD', '********' );
define( 'DB_HOST', 'databasewordp-instance-1.cv0flcl8po3i.us-east-1.rds.amazonaws.com' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY',         '******' );
define( 'SECURE_AUTH_KEY',  '*****' );
define( 'LOGGED_IN_KEY',    '*****' );
define( 'NONCE_KEY',        '*****' );
define( 'AUTH_SALT',        '*****' );
define( 'SECURE_AUTH_SALT', '*****' );
define( 'LOGGED_IN_SALT',   '*****' );
define( 'NONCE_SALT',       '*****' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

define( 'WP_LANG', 'es_CL' );

require_once ABSPATH . 'wp-settings.php';
