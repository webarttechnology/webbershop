<?php
/**
 * Dynamic CSS content.
 *
 * @package WordPress
 * @subpackage Multiple Products to Cart for WooCommerce
 * @since 1.0
 */

global $mpctable__;

$wmc_button_color        = ( get_option( 'wmc_button_color' ) ? get_option( 'wmc_button_color' ) : '#000' );
$wmc_button_text         = ( get_option( 'mpc_button_text_color' ) ? get_option( 'mpc_button_text_color' ) : '#fff' );
$header_pagination_color = ( get_option( 'mpc_head_text_color' ) ? get_option( 'mpc_head_text_color' ) : '#fff' );
$wmc_thead_back_color    = ( get_option( 'wmc_thead_back_color' ) ? get_option( 'wmc_thead_back_color' ) : '#000' );
$title_color             = get_option( 'mpc_protitle_color' );

$title_font_size    = get_option( 'mpc_protitle_font_size' );
$title_if_bold      = get_option( 'mpc_protitle_bold_font' );
$title_if_underline = get_option( 'mpc_protitle_underline' );

?>
<style type="text/css">
	.mpc-wrap thead tr th, .mpc-pagenumbers span.current{
		background: <?php echo esc_html( $wmc_thead_back_color ); ?>;
		color: <?php echo esc_html( $header_pagination_color ); ?>;
	}
	.mpc-button input.mpc-add-to-cart.wc-forward, button.mpce-single-add{
		background: <?php echo esc_html( $wmc_button_color ); ?>;
		color: <?php echo esc_html( $wmc_button_text ); ?>;
	}
	.product-image img{
		max-width: none !important;
	}
	.mpc-asearch img {
		display: inline-block;
		width: 26px;
		margin: -5px 15px;
	}
	<?php if ( isset( $title_color ) ) : ?>
	.mpc-product-title a{
		color: <?php echo esc_html( $title_color ); ?>;
	}
		<?php
	endif;

	if ( 'on' === get_option( 'wmca_inline_dropdown' ) ) :
		?>
	.mpc-wrap .variation-group > select{
		max-width: 100px;
	}
	.variation-group select{
		width: 100px;
	}
		<?php
	endif;

	?>
	.mpc-container .mpc-product-title a{
		<?php
		if ( ! empty( $title_font_size ) ) {
			echo sprintf( 'font-size: %spx;', esc_attr( $title_font_size ) );
		}
		if ( ! empty( $title_if_bold ) && 'on' === $title_if_bold ) {
			echo 'font-weight: bold;';
		}
		if ( ! empty( $title_if_underline ) && 'on' === $title_if_underline ) {
			echo 'text-decoration: underline;';
		} else {
			echo 'text-decoration: none;';
		}
		?>
	}
	@media screen and ( max-width: 767px ){
		td.mpc-product-select:before {
			content: "<?php echo esc_html( $mpctable__['labels']['wmc_ct_buy'] ); ?>";
			position: relative;
			top: 1px;
			right: 5px;
		}
	}
	<?php
	// custom styling hook.
	do_action( 'mpc_dynamic_css' );
	?>
</style>
