<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '$cG:OIbb,Vw-Np@eX.WMw31PSc11:2+E4HEERKZzJI*!m?1HOf|yT{ UR4.d|+gr' );
define( 'SECURE_AUTH_KEY',  '4LHf!U3 YXPXiZ><ZakH#z*@UmcSVN[ZB {63;S%4z3T<`GC.eBR6MCi!H;BKmBD' );
define( 'LOGGED_IN_KEY',    'U30t.E}{l{Y2YPrX8r=c_O8kX,zPWgw35ShbwVp$$f+0NLN+HGkqP!;7lm#}hx.5' );
define( 'NONCE_KEY',        '(u6cez;1GBuQuIvj<W_?I/G4wu0;,DNK@]#ib0>yjB#vz?@m*GR.?[EtD4|an)p6' );
define( 'AUTH_SALT',        '9@O:?yj:F:2ZX=-Wq5?Ph_NnjtRVy`iPiSM9`_!x`B}DKsNZ&!V<Oohz]mFI^3C5' );
define( 'SECURE_AUTH_SALT', ';`$Qp{{o=L.1vu52QOMdNORCVCBw|%w360k.`~w}Gy/-(fASS;-F(SG&~T}-9({ ' );
define( 'LOGGED_IN_SALT',   'ER%|2Xl#%O8JK+p}WoW`mrSFl=fZrKlJv.K<30s,1q{~<S,sA]z)h= !Z[7KG?6J' );
define( 'NONCE_SALT',       'M%WT?<@W%P(M_-:i*}kD.tC6SA.`w&D&6he&v2g O]bcbWnIVd%-:)xI=#9rw7@E' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
define('FS_METHOD', 'direct');
