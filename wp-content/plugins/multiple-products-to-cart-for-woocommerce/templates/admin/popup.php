<?php
/**
 * Admin popup templte.
 *
 * @package WordPress
 * @subpackage Multiple Products to Cart for WooCommerce
 * @since 1.0
 */

?>
<div id="mpcpop" class="mpc-popup">
	<div class="image-wrap">
		<span class="mpcpop-close">X</span>
		<div class="mpc-focus">
			<span></span> is a PRO feature.<br>
			<a href="<?php echo esc_url( $mpc__['prolink'] ); ?>" target="_blank">Black Cyber Sale - 70% OFF</a>
		</div>
		<div class="mpcex-features">
			<h4>More PRO features:</h4>
			<ul>
				<li>5+ new columns like SKU, stock, category etc</li>
				<li>Subscription product types are supported</li>
				<li>Single add to cart for each product</li>
				<li>Sort or hide any columns</li>
				<li>Custom product order</li>
			</ul>
			<a href="<?php echo esc_url( $mpc__['prolink'] ); ?>" target="_blank">See all PRO features</a>
		</div>
	</div>
</div>
