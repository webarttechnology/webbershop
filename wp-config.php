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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'webbershopdata' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '[<!Y=_]?T7mo+`$BnkHku+hNDZH@;5sVvg(Abp`nAFx/iE;pzds)O @P)/j8*[pu' );
define( 'SECURE_AUTH_KEY',  'EtrhmBMvN.$l**HA]a0~=EDQ*i8;5|LJ:BDxvh$n-:Y40T<R~G6X797a`^#dYzdi' );
define( 'LOGGED_IN_KEY',    'dgP*At,+50e}~Mv~x I,hBiDeqPJ[vT{<Vu2wHLtI+ywD#BI}65]55i<rI>[5L>L' );
define( 'NONCE_KEY',        'q~~X0=>pLyq[!<v[NAA8rs[dFQ!<N=u@DipOWm^D r%&J&HDi<zzZQQ3E2`!R~r#' );
define( 'AUTH_SALT',        ']G6Ud;EjFsNmz>FjUD;hs8Q1OFL~M*+D~|rmx4dU9<n#FD%=X2!uFu!jEWXJ/[h:' );
define( 'SECURE_AUTH_SALT', 'AngbA|D&#,mQ@4xk=>Rra6HVik8&sn;l}e:zYw3l][]`p!_fxkDZzA5S??q5ZH`P' );
define( 'LOGGED_IN_SALT',   'Tl4/6N}f3=VK+/a@%rne$a0QisRqh[@$mI}dF<0<z(oJ[.&TxO8=BRi[w4R75|{+' );
define( 'NONCE_SALT',       'jh`JuemoAC L0%,5JH!n+Zc1gr<~= /AF/08NXcJB! ##u+=^|8LZ%,S9W<X&2HJ' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
//define( 'WP_DEBUG', true );
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
