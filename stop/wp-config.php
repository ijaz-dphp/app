<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home/nginx/domains/belldial.softarts.co/public/wp-content/plugins/wp-super-cache/' );
define( 'DB_NAME', 'wp1294727566db_26532' );

/** Database username */
define( 'DB_USER', 'wpdb26532u2205' );

/** Database password */
define( 'DB_PASSWORD', 'wpdbt79F4Sb4AQJuKIatUkMp23099' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/** Enable core updates for minor releases (default) **/
define('DISABLE_WP_CRON', false);
define('WP_AUTO_UPDATE_CORE', 'minor' );
define('WP_POST_REVISIONS', 10 );
define('EMPTY_TRASH_DAYS', 10 );
define('WP_CRON_LOCK_TIMEOUT', 60 );
define('CONCATENATE_SCRIPTS', false);

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'C85y}Vcvf {d%`o?Ybeqe^5~&Zu;SI/n`fCLD>(|*Q=1#P>S gVJD_4jA{6Az4>:' );
define( 'SECURE_AUTH_KEY',   'fr^^d)Dk7w~x{|5{x$UEb5I.-F*i/Oi<2NY}R]i,@|,G+cH6)*Yl>7{~T;rPH{/i' );
define( 'LOGGED_IN_KEY',     'Way^B,r!e>k7Gh5b`Nuk3+ABoZmPY ^/&muLac/(JI_&br<mJ:WwDw$5:o%@J;z/' );
define( 'NONCE_KEY',         '[|)pcTesg>1f4mhV|}9!>AW?Q4^XRxR.utxO>k&.rp&:Cz!&3Ct?mr*xJ~:rLD^1' );
define( 'AUTH_SALT',         '{_!|>~~q$;^*I;X1.cP0**-Bn+Wj|&n&b582`Z^Y,(D 3zo/3Oxs[yUO52GjEGFM' );
define( 'SECURE_AUTH_SALT',  'S1g{?^~xFr*E3hMmi.nH]^`-Zl4J@ybjBn:q&t7C,P+?#T- m,7;lWW-moDIUpsW' );
define( 'LOGGED_IN_SALT',    'W:_sKKPM;ZTcFe5mOwv{/CAH6IY2c39+Ug>v)-U,Nw~Zs},FYj?G:+ DrB9RJWAK' );
define( 'NONCE_SALT',        'Io1E4n&(2(D{Oo|!{|q*L#UHn:94,8ZWXNu1y;:qeov`24D&fsk(CsPx3,@lymmV' );
define( 'WP_CACHE_KEY_SALT', 'EX9L;l)7J&_?y7{@q$Hs43.,Fv#DCrQ|BLZx_cYD>.Vd,-VKu|+]_J$+F_mzD)Kk' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '16626_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
