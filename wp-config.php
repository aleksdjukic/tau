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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'maniabest_taurus' );

/** Database username */
define( 'DB_USER', 'maniabes_user' );

/** Database password */
define( 'DB_PASSWORD', '123mania123123' );

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
define( 'AUTH_KEY',         '_b~yR2tQB)VdQybx+Z!s -KSxt?<]i)Yr:@(yrU2B</R.7~ZRpUpX6?V:l*.b:df' );
define( 'SECURE_AUTH_KEY',  '|^DKb%4F%3%fPn -fc$G>y/_MCw5_.sevnB[22e1bibBA87jk9.YbrV-)VKT!cDh' );
define( 'LOGGED_IN_KEY',    '^us5&I!zAp{g7V/B}A5d@^+U!Ns_5|3!}_0#gPCp5=%It^;n}Rlj)R{e^[4ip;Il' );
define( 'NONCE_KEY',        'l(1M95iPjz%:{l6Rjw|GYbIkviV{2yTs@XEISYp6Yz8hYR.R&MRiyc3I#Ax~~!U[' );
define( 'AUTH_SALT',        ',bK(!~5}37W=l#&fQb(H37uEb>?VDj70#$:w3t3&.1,&]}3U1M))C&&~I|a,m&II' );
define( 'SECURE_AUTH_SALT', 'c86p*wMR(CT,0J*;y]xc8:AZsegp=8Dm$ {&<4GY69ppW$SfS_VJR;KXmZ[T&J_l' );
define( 'LOGGED_IN_SALT',   'QipqoaZkZ>lf< zZhqa_>UA@~c8n84ua1[ze$2h)fER`g^_3!:Hy]7x<:yksjL 7' );
define( 'NONCE_SALT',       '~=(PV9A/Ae.^t1@J<!e*E-Q33u&E-990&ss^Z9gXHZ%M1cwS vw~5@,8#Px9ZQ(M' );

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
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
