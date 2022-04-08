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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'paw' );

/** MySQL database username */
define( 'DB_USER', 'paw' );

/** MySQL database password */
define( 'DB_PASSWORD', 'paw' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'KpVTOf<:H[{evQAP3(^r 2c|e|{?(n4M;%n8cJ]b*GlJIRu!AE0wDMcE~Vh10[$h' );
define( 'SECURE_AUTH_KEY',  '5SQuGZ$w6})|$%oTIC+p.8V_JS/C_]U.Sn^lfM2C6ey)vidFG,~@tkyBFPH~[m[&' );
define( 'LOGGED_IN_KEY',    '-*kj8Nsf.frRb(<pgss*V,nn<oN^%XylUcM[ox$t+<= 2QPphkc2F.HY+MsL>t~A' );
define( 'NONCE_KEY',        'F6b*fxOdE&vS;%htJ>PdasKdl)BhnsRKI8918aygBSN{K_3AFOffjfO(]&vApx!R' );
define( 'AUTH_SALT',        'Py!#3)FKi^gbN`hH:H$% /=u+:xbYI&rahT|cQaFfc*xv@11qV%JmxwNXFKE.D<6' );
define( 'SECURE_AUTH_SALT', 't?whC-sLtkEh2hxrYUh;+QY t;8W#$JoSF~HP_&e<=%tHT%G#BHt$]Dv8IJuAicI' );
define( 'LOGGED_IN_SALT',   '0H}=8CFl@U7KfF hbftc|KT! .ws_gQ(97GI{?C[E=oN4]zpD`PCg@?x7G;0{6=?' );
define( 'NONCE_SALT',       '^CN@u[q}D`l|u(<GLv7}*+4I-H;i@qq;ok{b[DCt7o&MOpZM`<Oko/,x6l[jRzw7' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
