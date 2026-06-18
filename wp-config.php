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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'S,.Cn4QNibFmzVxq0kse9j4[P}vj;>/f6+*0U4n3HedP/^@O(BL`NF7,Xf`kd:VJ' );
define( 'SECURE_AUTH_KEY',   'y=^i;;Xrn9:_h3H@dw2]OPT~KUGIFmPC|~OOVTJ0V;q$s#GQRv{4Ytu5LN`jz|!r' );
define( 'LOGGED_IN_KEY',     '*3}w% Tyt:2=@ Yv[yp1^JX^+~3+E2nQA-@=m_jmcu<88CP3xjXn/jv(*Y!0V[1G' );
define( 'NONCE_KEY',         'ZG*BR57#a@~H%1X+))eKCM9~mO`ffg+5nC6bCS4gr69BJwP,>tLHH_ toKbO%E$X' );
define( 'AUTH_SALT',         'N1*S&6oRy0! *v;X+v?aIA?r4.?1*q]ac)83 x5WE4&]nmtxXmR^]}uqhz+:`+s/' );
define( 'SECURE_AUTH_SALT',  'c=ncZ@GiL=>n+JvoOO<r*TtxZc8y0$WSTtO_vuXnl,Q+vh_:vqFMh!1H$R,cHSml' );
define( 'LOGGED_IN_SALT',    'I0q5nK+4NiK{=EJNVMeDURf2L%AwP7r7x?F7Z9z0a?Xvd2v ]uti6G4[er0/>Q*P' );
define( 'NONCE_SALT',        'u)DYjV4]d7uG.uo|b7t*5QLpFe#oHx&ed)goj,Ss]Rpys%leX2or[JAW|pUz3W^N' );
define( 'WP_CACHE_KEY_SALT', 'pG]M>dAM&7~>[&p.Y*&Gz<(2Uq@g|bSDuN3xi]A2H3DIvt<>podD>l&R);bBOJiJ' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


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

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
