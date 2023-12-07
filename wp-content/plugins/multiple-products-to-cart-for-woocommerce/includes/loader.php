<?php
/**
 * Plugin loading functions.
 *
 * @package WordPress
 * @subpackage Multiple Products to Cart for WooCommerce
 * @since 1.0
 */

global $mpc__;

add_action( 'admin_head', 'mpca_handle_admin_notice' );

// admin menu icon style.
add_action( 'admin_head', 'mpca_menu_icon_style' );

// Initialize the process. Everything starts from here!
add_action( 'init', 'mpc_activation_process_handler' );

// Activate and commence plugin.
register_activation_hook( MPC, 'mpc_activation' );

// Register deactivation process.
register_deactivation_hook( MPC, 'mpc_deactivation' );

/**
 * WooCommerce High Performance Order Storage (HPOS) compatibility enable.
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', MPC, true );
	}
} );

/**
 * EXTRA SUPPORT section for ADMIN CORE SUPOPRT
 *
 * Display what you want to show in the notice
 */
function mpc_client_feedback_notice() {
	global $mpc__;

	// get current page.
	$page = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

	// dynamic extra parameter adding beore adding new url parameters.
	$page .= strpos( $page, '?' ) !== false ? '&' : '?';
	?>
	<div class="notice notice-info is-dismissible">
		<h3>Multiple Products to Cart for WooCommerce</h3>
		<p>
			Excellent! You've been using <strong><a href="<?php echo esc_url( $mpc__['plugin']['review_link'] ); ?>">Multiple Products to Cart for WooCommerce</a></strong> for a while. We'd appreciate if you kindly rate us on <strong><a href="<?php echo esc_url( $mpc__['plugin']['review_link'] ); ?>">WordPress.org</a></strong>
		</p>
		<p>
			<a href="<?php echo esc_url( $mpc__['plugin']['review_link'] ); ?>" class="button-primary">Rate it</a> <a href="<?php echo esc_url( $page ); ?>mpca_rate_us=done&mpc_raten=<?php echo esc_attr( wp_create_nonce( 'mpc_rateing_nonce' ) ); ?>" class="button">Already Did</a> <a href="<?php echo esc_url( $page ); ?>mpca_rate_us=cancel&mpc_raten=<?php echo esc_attr( wp_create_nonce( 'mpc_rateing_nonce' ) ); ?>" class="button">Cancel</a>
		</p>
	</div>
	<?php
}

/**
 * Calculate date difference and some other accessories
 *
 * @param string $key | option meta key.
 * @param int    $notice_interval | Alarm after this day's difference.
 * @param string $skip_ | skip this value.
 */
function mpca_date_diff( $key, $notice_interval, $skip_ = '' ) {
	$value = get_option( $key );

	if ( empty( $value ) || '' === $value ) {

		// if skip value is meta value - return false.
		if ( '' !== $skip_ && $skip_ === $value ) {
			return false;
		} else {

			$c   = date_create( gmdate( 'Y-m-d' ) );
			$d   = date_create( $value );
			$dif = date_diff( $c, $d );
			$b   = (int) $dif->format( '%d' );

			// if days difference meets minimum given interval days - return true.
			if ( $b >= $notice_interval ) {
				return true;
			}
		}
	} else {
		add_option( $key, gmdate( 'Y-m-d' ) );
	}

	return false;
}

/**
 * Only for free version - inform about pro ( Immediate after free active Cancelable - trigger every 15 days)
 */
function mpc_pro_info() {
	global $mpc__;

	// get current page.
	$page = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

	// dynamic extra parameter adding beore adding new url parameters.
	$page .= strpos( $page, '?' ) !== false ? '&' : '?';
	?>
	<div class="notice notice-warning is-dismissible">
		<h3>Multiple Products to Cart for WooCommerce PRO</h3>
		<p>
			<strong>10+ PRO features available!</strong> Supercharge Your WooCommerce Stores with our light, fast and feature-rich version. See all <a href="<?php echo esc_url( $mpc__['prolink'] ); ?>" target="_blank">PRO features here</a>           
		</p>
		<p><a href="<?php echo esc_url( $mpc__['prolink'] ); ?>" class="button-primary">Get PRO</a> <a href="<?php echo esc_url( $page ); ?>mpca_notify_pro=cancel&mpc_proinfon=<?php echo esc_attr( wp_create_nonce( 'mpc_proinfo_nonce' ) ); ?>" class="button">Cancel</a></p>
	</div>
	<?php
}


/**
 * SUPPORT section of CORE ADMIN
 *
 * Save all admin notices for displaying later
 */
function mpca_handle_admin_notice() {
	global $mpc__;

	// only apply to admin MPC setting page.
	$screen = get_current_screen();
	if ( ! in_array( $screen->id, $mpc__['plugin']['screen'], true ) ) {
		return;
	}

	// Buffer only the notices.
	ob_start();

	do_action( 'admin_notices' );

	$content = ob_get_contents();
	ob_get_clean();

	// Keep the notices in global $mpc__.
	array_push( $mpc__['notice'], $content );

	// Remove all admin notices as we don't need to display in it's place.
	remove_all_actions( 'admin_notices' );
}

/**
 * If wc is inactive and mpc is active, show this notice
 */
function mpc_wc_auto_deactive_notice() {
	global $mpc__;
	?>
	<div class="error">
		<p>
			<a href="<?php echo esc_url( $mpc__['plugin']['free_mpc_url'] ); ?>" target="_blank">Multiple Products to Cart – WooCommerce Product Table</a> plugin can not be active. Please activate the following plugin first - <a href="<?php echo esc_url( $mpc__['plugin']['woo_url'] ); ?>" target="_blank">WooCommerce</a>
		</p>
	</div>
	<?php

}

/**
 * Notice - this plugin needs woocommerce plugin first
 */
function mpc_inactive_wc_notice() {
	global $mpc__;

	?>
	<div class="error">
		<p>Please activate the following plugin first <a href="<?php echo esc_url( $mpc__['plugin']['woo_url'] ); ?>" target="_blank">WooCommerce</a></p>
	</div>
	<?php

}

/**
 * Client feedback - rating
 */
function mpc_client_feedback() {
	global $mpc__;

	if ( isset( $_GET['mpc_raten'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['mpc_raten'] ) ), 'mpc_rating_nonce' ) ) {

		if ( isset( $_GET['mpca_rate_us'] ) ) {
			$task = sanitize_key( wp_unslash( $_GET['mpca_rate_us'] ) );

			if ( 'done' === $task ) {
				// never show this notice again.
				update_option( 'mpca_rate_us', 'done' );
			} elseif ( 'cancel' === $task ) {
				// show this notice in a week again.
				update_option( 'mpca_rate_us', gmdate( 'Y-m-d' ) );
			}
		}
	} elseif ( isset( $_GET['mpc_proinfon'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['mpc_proinfon'] ) ), 'mpc_proinfo_nonce' ) ) {

		if ( isset( $_GET['mpca_notify_pro'] ) ) {
			if ( 'cancel' === sanitize_key( wp_unslash( $_GET['mpca_notify_pro'] ) ) ) {
				update_option( 'mpca_notify_pro', gmdate( 'Y-m-d' ) );
			}
		}
	} else {
		if ( mpca_date_diff( 'mpca_rate_us', $mpc__['plugin']['notice_interval'], 'done' ) ) {
			// show notice to rate us after 15 days interval.
			add_action( 'admin_notices', 'mpc_client_feedback_notice' );

		}

		$proinfo = get_option( 'mpca_notify_pro' );
		if ( empty( $proinfo ) || '' === $proinfo ) {
			add_action( 'admin_notices', 'mpc_pro_info' );
		} else {
			if ( mpca_date_diff( 'mpca_notify_pro', $mpc__['plugin']['notice_interval'], '' ) ) {
				// show notice to inform about pro version after 15 days interval.
				add_action( 'admin_notices', 'mpc_pro_info' );

			}
		}
	}
}

/**
 * Check conditions before actiavation of the plugin
 */
function mpca_pre_activation() {
	$plugin = 'multiple-products-to-cart-for-woocommerce/multiple-products-to-cart-for-woocommerce.php';

	// check contingency if is plugin active founction not found | rare case.
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	// check if WC is active.
	$is_wc_active = is_plugin_active( 'woocommerce/woocommerce.php' );

	// check if our plugin is active.
	$is_mpc_active = is_plugin_active( $plugin );

	if ( ! $is_wc_active ) {
		if ( $is_mpc_active ) {

			deactivate_plugins( $plugin );
			add_action( 'admin_notices', 'mpc_wc_auto_deactive_notice' );

		} else {
			add_action( 'admin_notices', 'mpc_inactive_wc_notice' );
		}
		return false;
	}

	mpc_client_feedback();
	return true;
}

/**
 * Check if pro is installed
 */
function mpca_handle_pro() {
	global $mpc__;

	// don't have pro.
	$mpc__['has_pro'] = false;

	// Pro state.
	$mpc__['prostate'] = 'none';

	// change states.
	do_action( 'mpca_change_pro_state' );
}

/**
 * Add Settings to WooCommerce > Settings > Products > WC Multiple Cart
 *
 * @param array $links plugin extra links.
 */
function mpc_add_extra_plugin_links( $links ) {
	global $mpc__;

	$action_links = array();

	$action_links['settings'] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=mpc-settings' ) ), 'Settings' );

	if ( ! in_array( 'activated', explode( ' ', $mpc__['prostate'] ), true ) ) {
		$action_links['premium'] = sprintf( '<a href="%s" style="color: #FF8C00;font-weight: bold;text-transform: uppercase;">%s</a>', esc_url( $mpc__['prolink'] ), 'Get PRO' );
	}

	return array_merge( $action_links, $links );
}

/**
 * Add plugin description meta
 *
 * @param array  $links | plugin description links.
 * @param string $file | plugin base name.
 */
function mpc_plugin_desc_meta( $links, $file ) {

	// if it's not mpc plugin, return.
	if ( plugin_basename( MPC ) !== $file ) {
		return $links;
	}

	global $mpc__;
	$row_meta = array();

	$row_meta['docs']    = sprintf( '<a href="%s">Docs</a>', esc_url( $mpc__['plugin']['docs'] ) );
	$row_meta['apidocs'] = sprintf( '<a href="%s">Support</a>', esc_url( $mpc__['plugin']['request_quote'] ) );

	return array_merge( $links, $row_meta );
}

/**
 * Frontend script and style enqueuing
 */
function mpc_load_scripts() {
	global $mpc__;

	// enqueue style.
	wp_enqueue_style( 'mpc-frontend', plugin_dir_url( MPC ) . 'assets/frontend.css', array(), $mpc__['plugin']['version'], 'all' );

	// register script.
	wp_register_script( 'mpc-frontend', plugin_dir_url( MPC ) . 'assets/frontend.js', array( 'jquery' ), $mpc__['plugin']['version'], true );
	wp_enqueue_script( 'mpc-frontend', plugin_dir_url( MPC ) . 'assets/frontend.js', array( 'jquery' ), $mpc__['plugin']['version'], false );

	// handle localized variables.
	$redirect_url = get_option( 'wmc_redirect' );
	if ( '' === $redirect_url ) {
		$redirect_url = 'cart';
	}

	// add localized variables.
	$localaized_values = array(
		'ajaxurl'        => admin_url( 'admin-ajax.php' ),
		'redirect_url'   => $redirect_url,
		'page_limit'     => $mpc__['plugin']['page_limit'],
		'missed_option'  => get_option( 'wmc_missed_variation_text' ),
		'blank_submit'   => get_option( 'wmc_empty_form_text' ),
		'outofstock_txt' => '<p class="stock out-of-stock">' . __( 'Out of stock', 'woocommerce' ) . '</p>',
		'dp'             => get_option( 'woocommerce_price_num_decimals', 2 ),
		'dqty'           => get_option( 'wmca_default_quantity' ),
		'nonce'          => wp_create_nonce( 'ajax-nonce' ),
	);

	$localaized_values['key_fields'] = array(
		'orderby' => '.mpc-orderby',
	);

	// default quantity.
	if ( empty( $localaized_values['dqty'] ) || '' === $localaized_values['dqty'] ) {
		$localaized_values['dqty'] = 1;
	}

	if ( empty( $localaized_values['missed_option'] ) ) {
		$localaized_values['missed_option'] = 'Please select all options';
	}
	if ( empty( $localaized_values['blank_submit'] ) ) {
		$localaized_values['blank_submit'] = 'Please check one or more products';
	}

	// assets url.
	$localaized_values['imgassets'] = plugin_dir_url( MPC ) . 'assets/images/';

	// orderby supports.
	$localaized_values['orderby_ddown'] = array( 'price', 'title', 'date' );

	// apply filter.
	$localaized_values = apply_filters( 'mpca_update_local_vars', $localaized_values );

	// localize script.
	wp_localize_script( 'mpc-frontend', 'mpca_data', $localaized_values );
}

/**
 * Register and enqueue a custom stylesheet in the WordPress admin.
 */
function mpc_admin_enqueue_scripts() {
	global $mpc__;

	$screen = get_current_screen();

	// multiple-products_page_mpc-shortcodes.
	if ( ! in_array( $screen->id, $mpc__['plugin']['screen'], true ) ) {
		return;
	}

	// enqueue style.
	wp_register_style( 'mpc_admin_style', plugin_dir_url( MPC ) . 'assets/admin/admin.css', false, $mpc__['plugin']['version'] );
	wp_enqueue_style( 'mpc_admin_style' );

	// colorpicker style.
	wp_enqueue_style( 'wp-color-picker' );

	// colorpicker script.
	wp_enqueue_script( 'wp-color-picker' );

	wp_register_script( 'mpc_admin_script', plugin_dir_url( MPC ) . 'assets/admin/admin.js', array( 'jquery', 'jquery-ui-slider', 'jquery-ui-sortable' ), $mpc__['plugin']['version'], true );
	wp_enqueue_script( 'mpc_admin_script' );

	/**
	 * Choices JS
	 */
	wp_register_style( 'choices-css', plugin_dir_url( MPC ) . 'assets/lib/choices-js/choices.min.css', false, $mpc__['plugin']['version'] );
	wp_enqueue_style( 'choices-css' );

	wp_register_script( 'choices-js', plugin_dir_url( MPC ) . 'assets/lib/choices-js/choices.min.js', array( 'jquery' ), $mpc__['plugin']['version'], true );
	wp_enqueue_script( 'choices-js' );

	$var = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'has_pro' => $mpc__['has_pro'],
	);

	// apply hook for editing localized variables in admin script.
	$var = apply_filters( 'mpca_local_var', $var );

	wp_localize_script( 'mpc_admin_script', 'mpca_obj', $var );
}

/**
 * Handle Get PRO menu click event
 */
function mpc_getpro_menu() {
	global $mpc__;
	header( 'Location:', esc_url( $mpc__['prolink'] ) );
	exit;
}

/**
 * Menu to Add new shortcode admin settings page
 */
function mpc_new_shortcode_page() {
	$cls = new MPCSettings();
	$cls->load_settings( 'new-table' );
}

/**
 * Menu to Saved shortcode admin settings page
 */
function mpc_saved_shortcode_page() {
	$cls = new MPCSettings();
	$cls->load_settings( 'all-tables' );
}

/**
 * Top level menu callback function
 */
function mpc_render_admin_settings() {
	$cls = new MPCSettings();

	$tab = $cls->get_tab();

	if ( 'new-table' === $tab || 'all-tables' === $tab ) {
		$tab = 'general-settings';
	}

	$cls->load_settings( $tab );
}

/**
 * Add menu and submenu pages
 */
function mpca_add_admin_menu() {
	global $mpc__;

	// Main menu.
	add_menu_page(
		'Multiple Products to Cart Settings',
		'Multiple Products',
		'manage_options',
		'mpc-shortcodes',
		'mpc_saved_shortcode_page',
		plugin_dir_url( MPC ) . 'assets/images/admin-icon.svg',
		56
	);

	// main menu label change.
	add_submenu_page(
		'mpc-shortcodes',
		'Multiple Products to Cart - All product tables',
		'All Product Tables',
		'manage_options',
		'mpc-shortcodes'
	);

	// all product tables submenu.
	add_submenu_page(
		'mpc-shortcodes',
		'Multiple Products to Cart - Add product table',
		'Add Product Table',
		'manage_options',
		'mpc-shortcode',
		'mpc_new_shortcode_page'
	);

	add_submenu_page(
		'mpc-shortcodes',
		'Multiple Products to Cart - Settings',
		'Settings',
		'manage_options',
		'mpc-settings',
		'mpc_render_admin_settings'
	);

	if ( false === $mpc__['has_pro'] ) {
		add_submenu_page(
			'mpc-shortcodes',
			'Multiple Products to Cart - Get PRO',
			'<span style="color: #ff8921;">Get PRO</span>',
			'manage_options',
			'mpc-get-pro',
			'mpc_getpro_menu'
		);
	}
}

/**
 * Render sidebar content of admin settings page.
 *
 * @param string $default_path sidebar default path.
 */
function mpca_sidebar_( $default_path ) {
	include $default_path;
}

/**
 * Assign default values to admin fields if it's empty
 * Only works wither at plugin activation or update
 *
 * @param array $fields add default data to fields.
 */
function mpca_populate_fields( $fields ) {

	foreach ( $fields as $key => $def ) {

		if ( get_option( $key ) ) {
			update_option( $key, $def );
		} else {
			add_option( $key, $def );
		}
	}
}


/**
 * CORE ADMIN section
 *
 * Change admin menu icon.
 * Add custom link to Get PRO menu.
 */
function mpca_menu_icon_style() {
	global $mpc__;
	?>
	<style>
		#toplevel_page_mpc-shortcodes img {
			width: 20px;
			opacity:1!important;
		}
		.notice h3{
			margin-top:.5em;
			margin-bottom:0;
		}
	</style>
	<script>
		jQuery( document ).ready(function(){
			jQuery( '#toplevel_page_mpc-shortcodes a' ).each(function(){
				if( jQuery(this).text() == 'Get PRO' ){
					jQuery(this).attr( 'href', '<?php echo esc_url( $mpc__['prolink'] ); ?>' );
					jQuery(this).attr( 'target', '_blank' );
				}
			});
		});
	</script>
	<?php
}

/**
 * Start the plugin
 */
function mpc_activation_process_handler() {

	// check prerequisits.
	if ( ! mpca_pre_activation() ) {
		return;
	}

	mpca_handle_pro();

	// add extra links right under plug.
	add_filter( 'plugin_action_links_' . plugin_basename( MPC ), 'mpc_add_extra_plugin_links' );
	add_filter( 'plugin_row_meta', 'mpc_plugin_desc_meta', 10, 2 );

	// needs to be off the hook in the next version.
	include MPC_PATH . 'includes/class/class-mpcsettings.php';
	include MPC_PATH . 'includes/class/class-mpcshortcode.php';

	include MPC_PATH . 'includes/functions.php';
	include MPC_PATH . 'includes/hooks.php';

	// Enqueue frontend scripts and styles.
	add_action( 'wp_enqueue_scripts', 'mpc_load_scripts' );

	// Enqueue admin script and style.
	add_action( 'admin_enqueue_scripts', 'mpc_admin_enqueue_scripts' );

	// Add admin menu page.
	add_action( 'admin_menu', 'mpca_add_admin_menu' );

	// add to cart - if added any product/products.
	add_action( 'wp_loaded', 'mpc_products_add_to_cart', 15 );

	// add shortcode.
	add_shortcode( 'woo-multi-cart', 'mpc_multicart_shortcode' );

	add_action( 'mpca_sidebar', 'mpca_sidebar_', 10, 1 );
}

/**
 * Things to do for activating the plugin.
 */
function mpc_activation() {
	// main plugin activatio process handler.
	mpc_activation_process_handler();

	flush_rewrite_rules();

	// assign default fields value.
	mpca_populate_fields(
		array(
			'mpc_show_title_dopdown'      => 'on',
			'wmc_show_pagination_text'    => 'on',
			'wmc_show_products_filter'    => 'on',
			'wmc_show_all_select'         => 'on',
			'wmca_show_reset_btn'         => 'on',
			'wmca_show_header'            => 'on',
			'wmc_redirect'                => 'ajax',
			'mpc_show_total_price'        => 'on',
			'mpc_show_add_to_cart_button' => 'on',
			'mpc_add_to_cart_checkbox'    => 'on',
			'mpc_protitle_font_size'      => 16,
		)
	);
}

/**
 * Plugin deactivation handler
 */
function mpc_deactivation() {
	flush_rewrite_rules();
}
