<?php
/**
 * Shortcode table template.
 *
 * @package WordPress
 * @subpackage Multiple Products to Cart for WooCommerce
 * @since 1.0
 */

/**
 * This template can be overridden by copying it to yourtheme/templates/listing-list.php.
 * or with the hook `mpc_template_loader` you can even use custom location with custom file name, example
 *      apply_filters( 'mpc_template_loader', 'function_to_modify_modified_template_file' );
 */
global $mpctable__;
do_action( 'mpc_after_wrap' );
?>
<div class="woocommerce-page woocommerce mpc-container">
	<?php do_action( 'mpc_before_table' ); ?>
	<form class="mpc-cart" method="post" enctype="multipart/form-data" data-current_page="1">
		<div class="mpc-table-header">
			<?php do_action( 'mpc_table_header' ); ?>
		</div>    
		<?php mpc_display_table(); ?>
		<input type="hidden" name="mpc_cart_data" value="">
		<div class="mpc-table-footer">
			<?php do_action( 'mpc_table_footer' ); ?>
		</div>
	</form>
	<?php do_action( 'mpc_after_table' ); ?>
</div>
<?php do_action( 'mpc_after_wrap' ); ?>
