<?php
/**
 * Plugin Name: Multiple Products to Cart for WooCommerce
 * Plugin URI: https://webfixlab.com/plugins/multiple-products-to-cart-woocommerce-product-table/
 * Description: A truly lightweight EASY to use and super FAST WooCommerce product table solution to add multiple products to cart at once.
 * Author: WebFix Lab
 * Author URI: https://webfixlab.com/
 * Version: 7.0.2
 * Requires at least: 4.9
 * Tested up to: 6.4.1
 * Requires PHP: 7.0
 * Tags: product table, woocommerce product table,wc product table,products table,woocommerce table,multiple products,products table
 * WC requires at least: 3.6
 * WC tested up to: 8.3.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: multiple-products-to-cart-for-woocommerce
 *
 * @package WordPress
 * @subpackage Multiple Products to Cart for WooCommerce
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

// plugin path.
define( 'MPC', __FILE__ );
define( 'MPC_PATH', plugin_dir_path( MPC ) );

// Include admin settings functions.
require MPC_PATH . 'includes/core-data.php';

require MPC_PATH . 'includes/loader.php';
