<?php
/**
 * Frontend data structure
 *
 * @package WordPress
 * @subpackage Multiple Products to Cart for WooCommerce
 * @since 1.0
 */

global $mpctable__;

$mpctable__ = array(
	'image_sizes'     => array(
		'thumb' => 'thumbnail',
		'full'  => 'large', // or we should use full?
	),
	'quantity'        => array(
		'min' => 0,
		'max' => '', // leave it blank for undefined.
	),
	'orderby_options' => array(
		'menu_order' => 'Default sorting',
		'price-ASC'  => 'Price: Low to High',
		'price-DESC' => 'Price: High to Low',
	),
	'labels'          => array(
		'wmc_ct_image'                  => 'Image',
		'wmc_ct_product'                => 'Product',
		'wmc_ct_price'                  => 'Price',
		'wmc_ct_variation'              => 'Variation',
		'wmc_ct_quantity'               => 'Quantity',
		'wmc_ct_buy'                    => 'Buy',
		'wmc_ct_category'               => 'Category',
		'wmc_ct_stock'                  => 'Stock',
		'wmc_ct_tag'                    => 'Tag',
		'wmc_ct_sku'                    => 'SKU',
		'wmc_ct_rating'                 => 'Rating',
		'wmc_button_text'               => 'Add to Cart',
		'wmc_reset_button_text'         => 'Reset',
		'wmc_total_button_text'         => 'Total',
		'wmc_pagination_text'           => 'Showing Products',
		'wmc_select_all_text'           => 'Select All',
		'wmc_option_text'               => '',
		'wmc_empty_form_text'           => 'Please check one or more products',
		'wmc_thead_back_color'          => '', // get option value.
		'wmc_button_color'              => '', // same get option value.
		'wmc_empty_value_text'          => 'N/A',
		'wmc_missed_variation_text'     => 'Please select all options',
		'wmca_default_quantity'         => 0,
		'wmc_redirect'                  => '',
		'mpce_single_order_button_text' => 'Add',
		'mpcp_empty_result_text'        => 'Sorry! No products found.',
	),
	'options'         => array(
		// checkboxes here.
		'wmc_show_pagination_text'    => '',
		'wmc_show_products_filter'    => '',
		'wmc_show_all_select'         => '',
		'wmca_show_reset_btn'         => '',
		'wmca_single_cart'            => '',
		'wmca_inline_dropdown'        => '',
		'wmca_allow_sku_sort'         => '',
		'wmca_show_header'            => '',
		'mpc_show_title_dopdown'      => '',
		'mpc_show_new_quantity_box'   => '',
		'mpc_show_ajax_search'        => '',
		'mpc_show_ajax_cat_filter'    => '',
		'mpc_show_stock_out'          => '',
		'mpc_show_total_price'        => '',
		'mpc_show_add_to_cart_button' => '',
		'mpc_add_to_cart_checkbox'    => '',
		'mpc_show_variation_desc'     => '',
		'mpc_show_product_gallery'    => '',
		'mpc_show_cat_counter'        => '',
		'mpc_show_category_subheader' => '',
		'mpc_show_on_sale'            => '',
	),
	'woocommerce'     => array(
		'decimal_point' => get_option( 'woocommerce_price_num_decimals', 2 ),
	),
	'product_types'   => array( 'simple', 'variable' ),
	'default_imgs'    => array(
		'thumb' => wc_placeholder_img_src(),
		'full'  => wc_placeholder_img_src( 'full' ),
	),
);

/**
 * New way to find woocommerce placeholder image - if not found
 */
if ( ! file_exists( $mpctable__['default_imgs']['thumb'] ) ) {

	$updir = wp_get_upload_dir();
	$files = glob( $updir['basedir'] . '/woocommerce-placeholder*.png' );

	$sizes = array( 150, 300, 600 );
	foreach ( $sizes as $size ) {
		$newpath = $updir['basedir'] . '/woocommerce-placeholder-' . $size . 'x' . $size . '.png';

		if ( true === in_array( $newpath, $files, true ) ) {
			$mpctable__['default_imgs']['thumb'] = $updir['baseurl'] . '/woocommerce-placeholder-' . $size . 'x' . $size . '.png';
			break;
		}
	}
}

/**
 * Populate frontend data structure
 */
foreach ( $mpctable__['labels'] as $key => $label ) {
	$data = get_option( $key );
	if ( ! empty( $data ) && '' !== $data ) {
		$mpctable__['labels'][ $key ] = $data;
	}
}

/**
 * Option data( specially checkboxs )
 */
foreach ( $mpctable__['options'] as $key => $label ) {
	$value = get_option( $key );
	if ( ! empty( $value ) && '' !== $value ) {
		if ( 'on' === $value ) {
			$mpctable__['options'][ $key ] = true;
		} else {
			$mpctable__['options'][ $key ] = false;
		}
	}
}

/**
 * Default quantity
 */
if ( get_option( 'wmca_default_quantity' ) ) {
	$mpctable__['labels']['wmca_default_quantity'] = get_option( 'wmca_default_quantity' );
}

/**
 * Change orderby texts
 */
$a = get_option( 'mpc_sddt_default' );
if ( ! empty( $a ) && '' !== $a ) {
	$mpctable__['orderby_options']['menu_order'] = $a;
}

$a = get_option( 'mpc_sddt_price_asc' );
if ( ! empty( $a ) && '' !== $a ) {
	$mpctable__['orderby_options']['_price-ASC'] = $a;
}

$a = get_option( 'mpc_sddt_price_desc' );
if ( ! empty( $a ) && '' !== $a ) {
	$mpctable__['orderby_options']['_price-DESC'] = $a;
}

// Hook to modify frontend core data.
do_action( 'mpc_frontend_core_data' );

/**
 * Redirect to url after successful add to cart | default url = cart
 *
 * @param string $url will be available later.
 */
function mpc_go_to_designated_page( $url = '' ) {
	// if admin option set to cart.
	if ( 'cart' === get_option( 'wmc_redirect' ) ) {
		$url = wc_get_cart_url();
	}

	// filter - modify given url.
	$url = apply_filters( 'mpc_add_to_cart_redirect_url', $url );

	if ( ! empty( $url ) && '' !== $url ) {
		wp_safe_redirect( $url );
		exit;
	}
}

/**
 * SUPPORT FOR TEMPLATE FUNCTIONS.
 * Show total price with dynamic currency position | From WC | Used for difference html.
 *
 * @param string $total total text.
 */
function mpc_currency_total( $total = '' ) {

	// If not given anything, it will show 0 | else that price.
	if ( empty( $total ) ) {
		$total = 0;
	}

	// get currency position.
	$position = get_option( 'woocommerce_currency_pos' );

	?>
	<bdi>
		<?php if ( 'left' === $position || 'left_space' === $position ) : ?>
		<span class="currency"><?php echo 'left_space' === $position ? '&nbsp;' : ''; ?><?php echo wp_kses_post( get_woocommerce_currency_symbol() ); ?></span>
		<?php endif; ?>
		<span class="total-price"><?php echo esc_attr( $total ); ?></span>
		<?php if ( 'right' === $position || 'right_space' === $position ) : ?>
		<span class="currency"><?php echo wp_kses_post( get_woocommerce_currency_symbol() ); ?><?php echo 'right_space' === $position ? '&nbsp;' : ''; ?></span>
		<?php endif; ?>
	</bdi>
	<?php

}

/**
 * Display product image popup - frontend.
 */
function mpc_frontend_image_popup() {
	?>
	<div id="mpcpop" class="mpc-popup">
		<div class="image-wrap">
			<span class="dashicons dashicons-dismiss mpcpop-close"></span>
			<img src="">
			<h4 class="mpcpop-title"></h4>
			<p class="mpcpop-price"><?php mpc_currency_total(); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Display table row html content.
 *
 * @param int $id product id.
 */
function mpc_table_row( $id ) {
	global $mpctable__;

	// get current product id.
	$mpctable__['psid'] = $id;

	// get product data.
	$product = $mpctable__['products'][ $id ];

	echo sprintf(
		'<tr class="cart_item %s" data-varaition_id="0" data-type="%s" data-id="%s" stock="%s" data-price="%s">',
		esc_attr( $product['type'] ),
		esc_attr( $product['type'] ),
		esc_attr( $id ),
		esc_attr( $product['stock'] ),
		isset( $product['price_'] ) ? esc_attr( $product['price_'] ) : ''
	);

	// display each column at columns_list.
	foreach ( $mpctable__['columns_list'] as $key ) {
		do_action( 'mpc_table_column_' . str_replace( 'wmc_ct_', '', $key ) );
	}

	echo '</tr>';
}

/**
 * Handle category subheader section
 */
function mpc_table_category_subheader() {
	global $mpctable__;

	// check origin - (pro feature) | if isset - return.
	if ( isset( $mpctable__['attributes__']['origin'] ) && 'dropdown_filter' === $mpctable__['attributes__']['origin'] ) {
		return;
	}

	// if shortcode attribute `cats` not given or empty.
	if ( ! isset( $mpctable__['attributes']['cats'] ) || empty( $mpctable__['attributes']['cats'] ) ) {
		return false;
	}

	// if backend option is not enabled.
	if ( ! isset( $mpctable__['options']['mpc_show_category_subheader'] ) || false === $mpctable__['options']['mpc_show_category_subheader'] ) {
		return false;
	}

	// final precaution - if no product found.
	if ( ! isset( $mpctable__['products'] ) || empty( $mpctable__['products'] ) ) {
		return false;
	}

	// if not array - convert.
	$cats = $mpctable__['attributes']['cats'];
	if ( false === is_array( $cats ) ) {
		$cats = explode( ',', str_replace( ' ', '', $cats ) );
	}
	
	// populate with posts.
	$posts_in_cat = array();

	foreach ( $cats as $cat ) {
		$cat = (int) $cat;
		
		foreach ( $mpctable__['products'] as $id => $prod ) {

			// if product category(s) not found - skip.
			if ( ! isset( $prod['terms'] ) ) {
				continue;
			}

			if ( in_array( $cat, $prod['terms'], true ) && ! in_array( $id, array_values( $posts_in_cat ), true ) ) {
				$posts_in_cat[ $cat ][] = $id;
			}
		}
	}

	// render html.
	foreach ( $cats as $cat ) {

		// if no pre-processed posts found, skip this category.
		if ( ! isset( $posts_in_cat[ $cat ] ) || empty( $posts_in_cat[ $cat ] ) ) {
			continue;
		}

		// get term data ( name | description ).
		$term = get_term_by( 'id', $cat, 'product_cat' );
		?>
		<tr class="mpc-subhead">
			<td colspan="<?php echo esc_attr( count( $mpctable__['columns_list'] ) - 1 ); ?>">
				<h3><?php echo esc_html( $term->name ); ?></h3>
				<?php if ( ! empty( $term->description ) ) : ?>
				<p><?php echo esc_html( $term->description ); ?></p>
				<?php endif; ?>
			</td>
		</tr>
		<?php

		// display category products.
		foreach ( $posts_in_cat[ $cat ] as $id ) {

			// display table row html content.
			mpc_table_row( $id );
		}
	}

	return true;
}

/**
 * Template files overriding function
 *
 * @param string $default path of the file.
 *
 * You can override main table template file from your theme.
 * @example your-theme/templates/listing-list.php.
 */
function mpc_template_loader_( $default = '' ) {

	// check if theme has main template file, if yes use that.
	if ( strpos( $default, 'listing-list' ) ) {

		// check if theme has main template file.
		$path = get_stylesheet_directory() . '/templates/listing-list.php';

		// if oerride file exists, return that path.
		if ( file_exists( $path ) ) {
			return $path;
		}
	}

	return $default;
}

/**
 * Keep a copy of shortcode attribute as json object data before table html element
 */
function mpc_before_table_() {
	global $mpctable__;

	$atts = $mpctable__['attributes__'];

	?>
	<div class="mpc-table-query" data-atts="<?php echo ! empty( $mpctable__['attributes__'] ) && '' !== $mpctable__['attributes__'] ? wc_esc_json( wp_json_encode( $mpctable__['attributes__'] ) ) : ''; ?>"></div>
	<?php
}

/**
 * Product select checkbox
 */
function mpc_table_body_() {
	global $mpctable__;

	// for successful subheader section - skip loading normal table rows.
	if ( mpc_table_category_subheader() ) {
		return;
	}

	// display each product in a.
	foreach ( $mpctable__['products'] as $id => $prod ) {

		// display table row html content.
		mpc_table_row( $id );
	}
}

/**
 * Table header - show columns
 */
function mpc_table_title_columns_() {
	global $mpctable__;

	?>
	<thead>
		<tr>
		<?php foreach ( $mpctable__['columns_list'] as $key ) : ?>
			<th for="<?php echo esc_attr( $key ); ?>" class="mpc-product-<?php echo esc_attr( str_replace( 'wmc_ct_', '', $key ) ); ?>"><?php echo esc_html( $mpctable__['labels'][ $key ] ); ?></th>
		<?php endforeach; ?>
		</tr>
	</thead>
	<?php
}

/**
 * Image column action
 */
function mpc_table_column_image_() {
	global $mpctable__;

	// Current product.
	$id = $mpctable__['psid'];

	// Product data.
	$prod = $mpctable__['products'][ $id ];

	// Get actual dynamic image sizes (registered).
	$thumb = $mpctable__['image_sizes']['thumb'];
	$full  = $mpctable__['image_sizes']['full'];

	?>
	<td for="image" class="mpc-product-image" data-pimg-thumb="<?php echo esc_url( $prod['images'][ $thumb ] ); ?>" data-pimg-full="<?php echo esc_url( $prod['images'][ $full ] ); ?>">
		<div class="mpcpi-wrap">
			<?php if ( $mpctable__['options']['mpc_show_on_sale'] && $prod['on_sale'] ) : ?>
				<span class="wfl-sale">sale</span>
			<?php endif; ?>
			<img src="<?php echo esc_url( $prod['images'][ $thumb ] ); ?>" class="mpc-product-image attachment-<?php echo esc_attr( $thumb ); ?> size-<?php echo esc_attr( $thumb ); ?>" alt="" data-fullimage="<?php echo esc_url( $prod['images'][ $full ] ); ?>">
			<div class="mpc-popup-title" style="display: none;"><?php echo esc_html( $prod['title'] ); ?></div>
			<div class="mpc-popup-price" style="display: none;"><?php echo isset( $prod['price'] ) && ! empty( $prod['price'] ) ? wp_kses_post( $prod['price'] ) : ''; ?></div>
		</div>
		<?php do_action( 'init_mpc_gallery' ); ?>
	</td>
	<?php
}

/**
 * Display product title.
 */
function mpc_table_column_product_() {
	global $mpctable__;

	// Current product.
	$id = $mpctable__['psid'];

	// Product data.
	$prod = $mpctable__['products'][ $id ];

	$title = $prod['title'];

	?>
	<td for="title" class="mpc-product-name">
		<div class="mpc-product-title">
			<?php

			// Check if link should be added or not.
			if ( $mpctable__['attributes']['link'] ) {
				echo sprintf( '<a href="%s">%s</a>', esc_url( $prod['url'] ), esc_html( $title ) );
			} else {
				echo esc_html( $title );
			}

			// Check if we need to show description as well.
			if ( $mpctable__['attributes']['description'] ) :
				?>
			<div class="woocommerce-product-details__short-description">
				<p><?php echo esc_html( $prod['desc'] ); ?></p>
			</div>
			<?php endif; ?>
		</div>
	</td>
	<?php
}

/**
 * Display product price.
 */
function mpc_table_column_price_() {
	global $mpctable__;

	// Current product.
	$id = $mpctable__['psid'];

	// Product data.
	$prod = $mpctable__['products'][ $id ];
	?>
	<td for="price" class="mpc-product-price">
		<div class="mpc-single-price" style="display:none;">
			<?php

			// for variable products only.
			if ( strpos( $prod['type'], 'variable' ) !== false ) {
				mpc_currency_total();
			}
			?>
		</div>
		<div class="mpc-range">
			<?php echo isset( $prod['price'] ) && ! empty( $prod['price'] ) ? wp_kses_post( $prod['price'] ) : ''; ?>
		</div>
	</td>
	<?php
}

/**
 * Display variation attribute options.
 */
function mpc_display_variation_options() {
	global $mpctable__;

	// Current product.
	$id = $mpctable__['psid'];

	// Product data.
	$prod = $mpctable__['products'][ $id ];

	if ( ! isset( $prod['attributes'] ) ) {
		return;
	}
	?>
	<div class="row-variation-data" data-variation_data="<?php echo wc_esc_json( wp_json_encode( $prod['variation_data'] ) ); ?>"></div>
	<?php

	foreach ( $prod['attributes'] as $name => $data ) :
		$name_ = sanitize_title( $name );

		?>
	<div class="variation-group">
		<select class="<?php echo esc_attr( $name_ ); ?>" name="attribute_<?php echo esc_attr( $name_ . $id ); ?>" data-attribute_name="attribute_<?php echo esc_attr( $name_ ); ?>">
			<option value="">
			<?php
			echo '' !== $mpctable__['labels']['wmc_option_text'] ? esc_html( $mpctable__['labels']['wmc_option_text'] . ' ' ) : '';
			echo esc_html( $data['label'] );
			?>
			</option>
			<?php foreach ( $data['options'] as $option ) : ?>
				<option data-value="<?php echo esc_attr( $option['slug'] ); ?>" value="<?php echo esc_html( $option['value'] ); ?>"<?php echo true === $option['is_selected'] ? ' selected' : ''; ?>><?php echo esc_html( $option['name'] ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
		<?php

	endforeach;

}

/**
 * Display simple product blank text for variation scope in the table.
 */
function mpc_variation_scope_simple_product() {

	global $mpctable__;

	// Current product.
	$id = $mpctable__['psid'];

	// Product data.
	$prod = $mpctable__['products'][ $id ];

	if ( false !== strpos( $prod['type'], 'simple' ) ) {

		?>
	<span><?php echo esc_html( $mpctable__['labels']['wmc_empty_value_text'] ); ?></span>
		<?php

	}
}

/**
 * Display table variation column section.
 */
function mpc_table_column_variation_() {
	global $mpctable__;

	// if no variable product exists in current session, return.
	if ( false === $mpctable__['has_variation'] ) {
		return;
	}

	?>
	<td for="variation" class="mpc-product-variation">
		<?php

		// add custom variation html content.
		do_action( 'mpcp_custom_variation_html' );

		mpc_display_variation_options();
		?>
		<div class="clear-button"></div>
	</td>
	<?php
}

/**
 * Product price.
 */
function mpc_table_column_quantity_() {
	global $mpctable__;

	// Current product.
	$id = $mpctable__['psid'];

	// Product data.
	$prod = $mpctable__['products'][ $id ];

	// Default quantity.
	$defq = isset( $mpctable__['labels']['wmca_default_quantity'] ) ? (int) $mpctable__['labels']['wmca_default_quantity'] : $mpctable__['quantity']['min'];

	// Max quantity.
	$max = $mpctable__['quantity']['max'];

	// stock & max quantity.
	if ( isset( $prod['stock_'] ) ) {
		$max = $prod['stock_'];

		// If stock exceeds current default, change back to to stock.
		if ( $defq > $max ) {
			$defq = $max;
		}
	}

	// If sold individually, set quantity to 1.
	if ( true === $prod['sold_individually'] ) {
		$defq = 1;
		$max  = 1;
	}

	?>
	<td for="quantity" class="mpc-product-quantity">
		<div class="quantity">
			<input type="number" class="input-text qty text" step="1" min="<?php echo esc_attr( $mpctable__['quantity']['min'] ); ?>"<?php echo '' === $max ? '' : ' max="' . esc_attr( $max ) . '"'; ?> name="quantity<?php echo esc_attr( $id ); ?>" value="<?php echo esc_html( $defq ); ?>" data-default="<?php echo esc_html( $defq ); ?>" title="Quantity" size="4" inputmode="numeric"<?php echo isset( $prod['stock_'] ) ? ' data-current_stock="' . esc_html( $prod['stock_'] ) . '"' : ''; ?>>
		</div>
	</td>
	<?php
}

/**
 * Display stuf along or instead of buy checkbox.
 */
function mpc_table_buying_checkbox() {
	global $mpctable__;

	// Current product.
	$id = $mpctable__['psid'];

	// Product data.
	$prod = $mpctable__['products'][ $id ];

	?>
	<input type="checkbox" name="product_ids[]" value="<?php echo esc_attr( $id ); ?>" <?php echo true === $prod['is_selected'] ? ' checked="checked"' : ''; ?><?php echo false !== strpos( $prod['type'], 'variable' ) ? '' : ' data-price="' . esc_html( $prod['price_'] ) . '"'; ?>>
	<?php
}

/**
 * Product select checkbox.
 */
function mpc_table_column_buy_() {
	global $mpctable__;

	// Current product.
	$id = $mpctable__['psid'];

	// Product data.
	$prod = $mpctable__['products'][ $id ];

	// check if this product is selected or not.
	$checked = '';
	?>
	<td for="buy" class="mpc-product-select">
		<?php do_action( 'mpc_table_buy_btton' ); ?>
	</td>
	<?php
}

/**
 * Display table header orderby section.
 */
function mpc_table_header_orderby() {
	global $mpctable__;

	// check if admin option enabled for showing.
	if ( ! isset( $mpctable__['options']['wmc_show_products_filter'] ) || empty( $mpctable__['options']['wmc_show_products_filter'] ) ) {
		return;
	}

	if ( ! isset( $mpctable__['options']['mpc_show_title_dopdown'] ) || $mpctable__['options']['mpc_show_title_dopdown'] ) {

		// add title sorting option if needed.
		$mpctable__['orderby_options']['title-ASC'] = 'Title: A to Z';
		$a = get_option( 'mpc_sddt_title_asc' );
		if ( ! empty( $a ) && '' !== $a ) {
			$mpctable__['orderby_options']['title-ASC'] = $a;
		}

		$mpctable__['orderby_options']['title-DESC'] = 'Title: Z to A';
		$a = get_option( 'mpc_sddt_title_desc' );
		if ( ! empty( $a ) && '' !== $a ) {
			$mpctable__['orderby_options']['title-DESC'] = $a;
		}
	}

	?>
	<div class="mpc-sort">
		<select name="mpc_orderby" class="mpc-orderby" title="Table order by">
		<?php foreach ( $mpctable__['orderby_options'] as $slug => $label ) : ?>
			<option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></option>
		<?php endforeach; ?>
		</select>
		<input type="hidden" name="paged" value="1" />
	</div>
	<?php
}

/**
 * Display table header check all product section.
 */
function mpc_table_header_check_all() {
	global $mpctable__;

	// check if select all checkbox is enabled.
	if ( isset( $mpctable__['options']['wmc_show_all_select'] ) && $mpctable__['options']['wmc_show_all_select'] ) :

		?>
	<div class="mpc-all-select">
		<label><?php echo esc_html( $mpctable__['labels']['wmc_select_all_text'] ); ?></label>
		<input type="checkbox" class="mpc-check-all">
	</div>
		<?php

	endif;
}

/**
 * Add dropdown filter before table.
 */
function mpc_table_header_() {
	global $mpctable__;

	do_action( 'mpc_table_header_content' );
}

/**
 * Get pagination pages array
 *
 * @param int $paged current page.
 * @param int $limit maximum pagination page number.
 */
function mpc_pagination_pages( $paged, $limit ) {
	if ( 1 === $limit ) {
		return array();
	} elseif ( $limit < 5 ) {
		return range( 1, $limit );
	}

	$pages = array( 1, $limit ); // all pages to display in the pagination list.

	$pages = array_merge( $pages, range( $paged - 1, $paged + 1 ) );
	$pages = array_unique( $pages );
	sort( $pages );

	return $pages;
}

/**
 * Display pagination page numbers.
 */
function mpc_display_pagination_numbers() {
	global $mpctable__;

	// get current page and maximum page number.
	$paged    = ! empty( $mpctable__['paged'] ) ? (int) $mpctable__['paged'] : 1;
	$max_page = (int) $mpctable__['query']['max_page'];

	$pages = mpc_pagination_pages( $paged, $max_page );

	if ( empty( $pages ) || ! is_array( $pages ) ) {
		return;
	}

	// current pages counter.
	$total_pages = count( $pages );
	?>
	<div class="mpc-pagenumbers" data-max_page="<?php echo esc_attr( $mpctable__['query']['max_page'] ); ?>">
		<?php
		for ( $i = 0; $i < $total_pages; $i++ ) {
			if ( 0 === $pages[ $i ] || $pages[ $i ] > $max_page ) {
				continue;
			}

			if ( $i > 0 && abs( $pages[ ( $i - 1 ) ] - $pages[ $i ] ) > 1 ) {
				echo '...';
			}
			?>
			<span <?php echo $pages[ $i ] === $paged ? 'class="current"' : ''; ?>>
				<?php echo esc_attr( $pages[ $i ] ); ?>
			</span>
			<?php
		}
		?>
	</div>
	<?php
}

/**
 * Display pagination numbering.
 */
function render_mpc_pagination() {
	global $mpctable__;

	if ( ! $mpctable__['attributes']['pagination'] || $mpctable__['query']['total'] <= $mpctable__['attributes']['limit'] ) {
		return;
	}

	?>
	<div class="mpc-pagination">
		<div class="mpc-inner-pagination">
			<?php mpc_display_pagination_numbers(); ?>
		</div>
	</div>
	<?php
}

/**
 * Displayy total price.
 */
function mpc_table_total_() {
	global $mpctable__;

	?>
	<div class="total-row">
		<span class="total-label"><?php echo esc_html( $mpctable__['labels']['wmc_total_button_text'] ); ?></span>
		<span class="mpc-total"><?php mpc_currency_total(); ?></span>
	</div>
	<?php
}

/**
 * Table add to cart button.
 */
function mpc_table_add_to_cart_button_() {
	global $mpctable__;

	?>
	<input type="submit" class="mpc-add-to-cart single_add_to_cart_button button alt wc-forward" name="proceed" value="<?php echo esc_html( $mpctable__['labels']['wmc_button_text'] ); ?>" />
	<?php

}

/**
 * Display pagination text.
 */
function mpc_display_table_pagination_range() {
	global $mpctable__;

	// if admin settings option is not enabled return.
	if ( ! $mpctable__['options']['wmc_show_pagination_text'] ) {
		return;
	}

	// if shortcode attribute is set to no pagination.
	if ( ! $mpctable__['attributes']['pagination'] ) {
		return;
	}

	// and finally, if pagination enabled, current range is within set limit.
	if ( $mpctable__['query']['total'] <= $mpctable__['attributes']['limit'] ) {
		return;
	}

	// Current page.
	$page = $mpctable__['paged'];
	if ( empty( $page ) || '' === $page ) {
		$page = 1;
	}

	// display current page products range.
	$product_range = '';

	if ( $mpctable__['query']['total'] > $mpctable__['attributes']['limit'] ) {
		$product_range = ( ( $page - 1 ) * $mpctable__['attributes']['limit'] + 1 ) . ' - ';

		// check if max range is within max.
		if ( ( $page * $mpctable__['attributes']['limit'] ) <= $mpctable__['query']['total'] ) {
			$product_range .= ( $page * $mpctable__['attributes']['limit'] );

		} else {
			$product_range .= $mpctable__['query']['total'];
		}
	} else {
		$product_range = ( ( $page - 1 ) * $mpctable__['attributes']['limit'] + 1 ) . ' - ' . $mpctable__['query']['total'];
	}

	?>
	<div class="mpc-product-range" data-page_limit="<?php echo esc_attr( $mpctable__['attributes']['limit'] ); ?>">
		<p>
			<?php echo esc_html( $mpctable__['labels']['wmc_pagination_text'] ); ?> <strong><span class="ranges"><?php echo esc_html( $product_range ); ?></span> / <span class="max_product"><?php echo esc_attr( $mpctable__['query']['total'] ); ?></soan></strong>
		</p>
	</div>
	<?php
}

/**
 * Footer content of the table.
 */
function mpc_table_footer_() {
	global $mpctable__;

	// display total price.
	do_action( 'mpc_table_total' );

	?>
	<div class="mpc-button">
		<?php mpc_display_table_pagination_range(); ?>
		<div>
			<input type="hidden" name="add_more_to_cart" value="1">
			<?php

			// check if we need to show the reset button.
			if ( true === $mpctable__['options']['wmca_show_reset_btn'] ) :

				?>
				<input type="reset" class="mpc-reset" value="<?php echo esc_html( $mpctable__['labels']['wmc_reset_button_text'] ); ?>">
				<?php

			endif;

			do_action( 'mpc_table_add_to_cart_button' );

			?>
		</div>
	</div>
	<?php do_action( 'render_mpc_pagination' ); ?>
	<div class="mpc-table-query" data-atts="<?php echo ! empty( $mpctable__['attributes__'] ) && '' !== $mpctable__['attributes__'] ? wc_esc_json( wp_json_encode( $mpctable__['attributes__'] ) ) : ''; ?>"></div>
	<?php
}

/**
 * Display frontend table html.
 */
function mpc_display_table() {
	global $mpctable__;

	// no image class.
	$cls = '';
	if ( false === in_array( 'wmc_ct_image', $mpctable__['columns_list'], true ) ) {
		$cls = 'mpc-no-image';
	}

	?>
	<table class="mpc-wrap <?php echo esc_attr( $cls ); ?>" cellspacing="0">
		<?php do_action( 'mpc_table_title_columns' ); ?>
		<tbody>
			<?php do_action( 'mpc_table_body' ); ?>
		</tbody>
	</table>
	<?php

}



/**
 * CORE PLUGIN FUNCTIONS
 *
 * Get variation data | for using as data attribute on frontend
 *
 * @param array $product product data object.
 */
function mpc_get_variation_data( $product ) {
	global $mpctable__;

	$childrens = $product->get_children();
	if ( ! $childrens ) {
		return array();
	}

	$data = array();

	foreach ( $childrens as $child_id ) {
		$variation = wc_get_product( $child_id );

		// if not in stock or not and enabled to show out of stock.
		if ( ( ! $variation || ! $variation->is_in_stock() ) && ! $mpctable__['options']['mpc_show_stock_out'] ) {
			continue;
		}

		// get all options per attribute.
		$atts = $variation->get_attributes();

		// sanitize.
		$atts_sanitized = array();
		foreach ( $atts as $key => $value ) {
			$atts_sanitized[ $key ] = sanitize_title( $value );
		}

		$c = array( 'attributes' => $atts_sanitized );

		// get variation price.
		$price = $variation->get_price();

		$price = (float) $price;

		// if subscription type.
		if ( strpos( $product->get_type(), 'subscription' ) !== false ) {
			$supfee = get_post_meta( $child_id, '_subscription_sign_up_fee', true );
			if ( '' !== $supfee && ! empty( $supfee ) ) {
				$supfee = (float) $supfee;
				$price += $supfee;
			}
		}
		$c['price'] = $price;

		// get variation image, if no image set woocommerce default image.
		$image_id = get_post_meta( $child_id, '_thumbnail_id', true );
		if ( $image_id ) {
			$c['image']['thumbnail'] = wp_get_attachment_image_src( $image_id, 'thumbnail' )[0];
			$c['image']['full']      = wp_get_attachment_image_src( $image_id, 'large' )[0];
		} else {
			// check beforehand.
			$c['image']['thumbnail'] = $mpctable__['default_imgs']['thumb'];
			$c['image']['full']      = $mpctable__['default_imgs']['full'];
		}

		// hook for modifying image thumbnail.
		$img                     = apply_filters(
			'mpc_table_thumbnail',
			array(
				'image_id'   => $image_id,
				'thumbnail'  => $c['image']['thumbnail'],
				'full'       => $c['image']['full'],
				'thumb_size' => 'thumbnail',
			)
		);
		$c['image']['thumbnail'] = $img['thumbnail'];

		// get sku.
		$c['sku'] = $variation->get_sku();

		// stock.
		$c['stock_status'] = $variation->get_stock_status();
		$c['stock']        = $variation->get_stock_quantity();
		if ( empty( $c['stock'] ) || '' === $c['stock'] ) {
			$c['stock'] = 'In stock';
		} else {
			$c['stock'] .= ' in stocks';
		}

		// variation short description.
		if ( $mpctable__['options']['mpc_show_variation_desc'] ) {
			$desc = $variation->get_description();
			if ( ! empty( $desc ) ) {
				$c['desc'] = $desc;
			}
		}

		// hook to add additional variation data.
		$c                 = apply_filters( 'mpc_modify_js_data', $c, $variation );
		$data[ $child_id ] = $c;
	}

	return $data;
}

/**
 * Check if given variation is in stock, returns true | false
 *
 * @param array   $product | product data object.
 * @param string  $attr_name | variation attribute name.
 * @param string  $attr_value | variation attribute value.
 * @param boolean $return | wheather to return variation id or just true/false.
 */
function mpc_if_variation_in_stock( $product, $attr_name, $attr_value, $return = false ) {
	$is_in_stock = false;

	// for keeping variation id.
	$variation_id = 0;

	// get all avariations.
	$childrens = $product->get_children();

	// sanitize everything first.
	$attr_name  = sanitize_title( $attr_name );
	$attr_value = sanitize_title( $attr_value );

	foreach ( $childrens as $child_id ) {
		if ( 0 !== $variation_id ) {
			break;
		}

		// get variation type object.
		$variation = wc_get_product( $child_id );

		foreach ( $variation->get_attributes() as $name => $option ) {

			// for checking given option of current attribute.
			if ( sanitize_title( $name ) === $attr_name ) {

				if ( empty( $option ) || sanitize_title( $option ) === $attr_value ) {

					// keep variation id in $variation_id.
					$variation_id = $child_id;

					if ( $variation->is_in_stock() ) {
						$is_in_stock = true;
					}

					// modify flag for third party interjection.
					$is_in_stock = apply_filters( 'mpc_variation_status', $is_in_stock, $variation );
					break;

				} elseif ( empty( $option ) ) {

					// if "any option" enabled | nothing more.
					$is_in_stock = true;
					break;
				}
			}
		}
	}

	if ( false === $return ) {
		return $variation_id;
	} else {
		return $is_in_stock;
	}
}

/**
 * Sort variation option in the order they are in the backend
 *
 * @param array  $product       product data.
 * @param string $sanitize_name variation attribute sanitized name.
 * @param array  $options       variation attribute options.
 * @param array  $terms         variation attributes in term format.
 */
function mpc_sort_variation_options( $product, $sanitize_name, $options, $terms ) {
	$ids = $product->get_attributes()[ $sanitize_name ]->get_options();

	global $wpdb;
	$ids_string = implode( ',', $ids );
	$result     = $wpdb->get_results(
		$wpdb->prepare( "SELECT term_id, meta_value FROM {$wpdb->termmeta} WHERE term_id IN (%s) AND meta_key='order' ORDER BY meta_value", $ids_string ),
		ARRAY_A
	); // WPCS: db call ok. // WPCS: cache ok.

	// new sorted attribute options.
	$sorted_options = array();

	// which term ids have already been sorted.
	$sorted_ids = array();

	if ( empty( $result ) || ! is_array( $result ) ) {
		$sorted_ids = $ids;
	} else {
		foreach ( $result as $row ) {
			array_push( $sorted_ids, $row['term_id'] );
		}
		$sorted_ids = array_merge( $sorted_ids, $ids );
		$sorted_ids = array_unique( $sorted_ids );
	}

	foreach ( $sorted_ids as $id ) {
		foreach ( $terms as $term ) {
			if ( $term->term_id === (int) $id ) {
				$sorted_options[] = array(
					'name' => esc_html( $term->name ),
					'slug' => esc_attr( $term->slug ),
				);
			}
		}
	}

	return $sorted_options;
}

/**
 * Template overriding function with auto load of main template from theme directory
 *
 * @example theme/templates/listing-list.php.
 *
 * @param string $default path to override default listing-list.php file path.
 */
function mpc_template_loader( $default = '' ) {

	// check if this is main template file.
	if ( strpos( $default, 'listing-list' ) ) {

		// check if theme has main template file.
		$path = get_stylesheet_directory() . '/templates/listing-list.php';

		if ( file_exists( $path ) ) {
			return $path;
		}
	}

	// impending file_exists validation - not done.
	return $default;
}


/**
 * AJAX section
 *
 * Format error while adding to cart.
 */
function mpc_format_errors() {
	$notices = wc_get_notices( 'error' );

	if ( empty( $notices ) || ! is_array( $notices ) ) {
		$notices = array( 'There was an error adding to the cart. Please try again.' );
	}

	$result    = '';
	$error_fmt = apply_filters( 'wc_product_table_cart_error_format', '<span class="cart-error">%s</span>' );

	foreach ( $notices as $notice ) {
		$notice_text = isset( $notice['notice'] ) ? $notice['notice'] : $notice;
		$result     .= sprintf( $error_fmt, $notice_text );
	}

	wc_clear_notices();
	return $result;
}

/**
 * Custom add to cart support with mini cart.
 */
function mpc_get_refreshed_fragments() {
	// Get mini cart.
	ob_start();

	woocommerce_mini_cart();

	$mini_cart    = ob_get_clean();
	$cart_session = WC()->cart->get_cart_for_session();

	// Fragments and mini cart are returned.
	$data = array(
		'fragments' => apply_filters(
			'woocommerce_add_to_cart_fragments',
			array(
				'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
			)
		),
		'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', $cart_session ? md5( wp_json_encode( $cart_session ) ) : '', $cart_session ),
	);

	return $data;
}

/**
 * Ajax actual product add to cart handler.
 *
 * @param array  $data   ajax calling data.
 * @param string $method certain method of calling ajax.
 */
function mpc_handle_add_to_cart( $data, $method ) {
	if ( empty( $data ) ) {
		return;
	}
	$product_ids = array();

	// for handling titles.
	$titles = array();
	foreach ( $data as $product_id => $product ) {
		$flag = false;
		$key  = '';

		if ( strpos( $product['type'], 'variable' ) !== false && isset( $product['variation_id'] ) ) {

			if ( false !== WC()->cart->add_to_cart( $product_id, $product['quantity'], $product['variation_id'], $product['attributes'] ) ) {
				$flag = true;
			}
		} else {
			$key = WC()->cart->add_to_cart( $product_id, $product['quantity'] );
			if ( false !== $key ) {
				$flag = true;
			}
		}

		do_action( 'mpc_after_add_to_cart', $product_id, $key );

		if ( true === $flag ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			array_push( $product_ids, $product_id );

			$qty = $product['quantity'];

			/* translators: %s: product name */
			$titles[] = apply_filters( 'woocommerce_add_to_cart_qty_html', ( $qty > 1 ? absint( $qty ) . ' &times; ' : '' ), $product_id ) . apply_filters( 'woocommerce_add_to_cart_item_name_in_quotes', sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'woocommerce' ), wp_strip_all_tags( get_the_title( $product_id ) ) ), $product_id );
		}
	}

	$message = '';

	if ( count( $titles ) > 0 ) {

		$titles = array_filter( $titles );

		/* translators: %s: product name */
		$added_text = sprintf( _n( '%s has been added to your cart.', '%s have been added to your cart.', count( $product_ids ), 'woocommerce' ), wc_format_list_of_items( $titles ) );

		$message = sprintf( '<a href="%s" tabindex="1" class="button wc-forward">%s</a> %s', esc_url( wc_get_page_permalink( 'cart' ) ), 'View cart', esc_html( $added_text ) );

	} else {
		$message = mpc_format_errors();
	}

	if ( 'ajax' === $method ) {
		$resonse = array();
		if ( count( $titles ) > 0 ) {
			$resonse                 = mpc_get_refreshed_fragments();
			$resonse['cart_message'] = $message;
		} else {
			$resonse = array( 'error_message' => mpc_format_errors() );
		}
		$resonse['req'] = $data;

		wp_send_json( $resonse );
	} else {
		wc_add_notice( $message );
		mpc_go_to_designated_page();
	}
}

/**
 * Add to cart - if added any product/products
 */
function mpc_products_add_to_cart() {
	if ( ! class_exists( 'WC_Form_Handler' ) ) {
		return;
	}

	// only for mpc plugin add to cart event.
	if ( ! isset( $_REQUEST['mpc_cart_data'] ) ) {
		return;
	}

	remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );

	$d = sanitize_text_field( wp_unslash( $_REQUEST['mpc_cart_data'] ) );
	$d = json_decode( $d, true );

	mpc_handle_add_to_cart( $d, 'submission' );
}

/**
 * Convert true false to boolean value.
 *
 * @param array $atts shortcode attributes.
 */
function mpc_redefine_boolean_values( $atts ) {

	if ( false === is_array( $atts ) ) {
		return $atts;
	}

	foreach ( $atts as $key => $value ) {
		if ( ! isset( $value ) || empty( $value ) || '' === $value ) {
			continue;
		}

		if ( gettype( $value ) === 'string' ) {
			$value = sanitize_title( $value );

			// convert string true | false attribute value to boolean.
			if ( strpos( $value, 'true' ) !== false ) {
				$atts[ $key ] = true;
			} elseif ( strpos( $value, 'false' ) !== false ) {
				$atts[ $key ] = false;
			}
		}
	}

	return $atts;
}

/**
 * Process shortcode attributes, user input and validate some values ( not all ).
 *
 * @param array $atts shortcode attributes.
 */
function mpc_get_shortcode_attributes( $atts ) {

	/**
	 * Reference attributes
	 *
	 * @since @version 5.1.0 `image` attribute has been deprecated
	 */
	$ref_atts = array(
		'table'       => '',
		'limit'       => 10,
		'orderby'     => '',
		'order'       => 'DESC',
		'ids'         => '',
		'cats'        => '',
		'type'        => 'all',
		'link'        => 'true',
		'description' => 'false',
		'selected'    => '',
		'pagination'  => 'true',
	);

	/**
	 * Hook for editing shortcode attributes
	 * add or remove support for existing or extra attributes
	 */
	$ref_atts = apply_filters( 'mpc_filter_attributes', $ref_atts );

	$atts = shortcode_atts( $ref_atts, $atts, 'woo-multi-cart' );

	$atts = mpc_redefine_boolean_values( $atts );

	// comma separated attributes.
	$cs_atts = array( 'selected', 'ids', 'cats', 'type' );
	foreach ( $cs_atts as $type ) {

		// for selected all, skip.
		if ( 'selected' === $type && 'all' === $atts[ $type ] ) {
			continue;
		}

		if ( isset( $atts[ $type ] ) && '' !== $atts[ $type ] ) {

			$tmp   = str_replace( ' ', '', $atts[ $type ] );
			$tmp   = explode( ',', $tmp );
			$tmp_a = array();

			foreach ( $tmp as $a ) {
				if ( false === in_array( $a, $tmp_a, true ) ) {
					array_push( $tmp_a, $a );
				}
			}

			$atts[ $type ] = $tmp_a;
		}
	}

	return $atts;
}

/**
 * Build WP_Query arguments from give shortcode attribute
 *
 * @param array $atts shortcode attributes.
 * @param int   $paged pagination page number.
 */
function mpc_get_query_attributes( $atts, $paged ) {
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => (int) $atts['limit'],
		'paged'          => $paged,
	);

	if( ! empty( $args['posts_per_page'] ) && $args['posts_per_page'] > 100 ){
		$args['posts_per_page'] = 100;
	}

	// if pagination set to false, set limit to 100.
	if ( isset( $atts['pagination'] ) && false === $atts['pagination'] ) {
		$args['posts_per_page'] = 100;
	} else {
		$args['posts_per_page'] = (int) $args['posts_per_page'];
	}

	// special ordering support.
	$special_support = apply_filters( 'mpc_get_orderby_list', array( 'price' ) );

	if ( in_array( $atts['orderby'], array( 'title', 'date' ), true ) ) {
		$args['orderby'] = ( '' !== $atts['orderby'] ? $atts['orderby'] : 'date' );
	}
	if ( isset( $atts['order'] ) ) {
		$args['order'] = ( '' !== $atts['order'] ? strtoupper( $atts['order'] ) : 'DESC' );
	}

	// order by price.
	if ( in_array( $atts['orderby'], $special_support, true ) ) {

		// get actual key instead of given stuff.
		if ( 'price' === $atts['orderby'] ) {
			$args['meta_key'] = '_price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		} else {
			$args['meta_key'] = $atts['orderby']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		}

		$args['orderby'] = 'meta_value_num';
	}

	$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		array(
			'key'      => '_stock_status',
			'value'    => 'instock',
			'complare' => '=',
		),
		array(
			'key'     => '_price',
			'compare' => 'EXISTS',
		),
	);

	if ( isset( $atts['ids'] ) && '' !== $atts['ids'] ) {
		$args['post__in'] = $atts['ids'];
	}

	/**
	 * Extra care for taxonoy
	 * two types of taxonomy here product_cat and product_type
	 */
	if ( isset( $atts['type'] ) && '' !== $atts['type'] ) {

		// special product type support.
		$types_support = apply_filters( 'mpc_change_product_types', array( 'simple', 'variable' ) );

		$types = $atts['type'];
		if ( in_array( 'all', $types, true ) ) {
			$types = $types_support;
		} else {
			$temp = array();
			foreach ( $types as $t ) {
				$t = sanitize_title( $t );
				if ( in_array( $t, $types_support, true ) ) {
					array_push( $temp, $t );
				}
			}
			$types = $temp;
		}

		$args['tax_query'] = array( 'relation' => 'AND' ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

		$args['tax_query'][] = array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms'    => $types,
		);
	}

	if ( isset( $atts['cats'] ) && '' !== $atts['cats'] ) {
		$tids = array();

		if ( ! is_array( $atts['cats'] ) ) {
			$atts['cats'] = explode( ',', str_replace( ' ', '', $atts['cats'] ) );
		}

		foreach ( $atts['cats'] as $tid ) {

			$catobj = get_term_by( 'id', $tid, 'product_cat' );
			if ( ! empty( $catobj ) && isset( $catobj->term_id ) ) {
				$tids[] = $catobj->term_id;
			} else {
				$catobj = get_term_by( 'slug', $tid, 'product_cat' );
				if ( ! empty( $catobj ) && isset( $catobj->term_id ) ) {
					$tids[] = $catobj->term_id;
				}
			}
		}

		// check if integer ids provided.
		if ( ! empty( $tids ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $tids,
			);
		}
	}

	return apply_filters( 'mpc_modify_query', $args, $atts );
}

/**
 * Get all product data in the table before loading main template file
 *
 * @param array $products all given products.
 */
function mpc_get_table_data_array( $products ) {
	global $mpctable__;

	// MPC Settings Class.
	$cls = new MPCSettings();

	// starting point of table data - get a list of column names.
	$mpctable__['columns_list'] = $cls->sorted_columns( true );

	// check if products has variable type in it.
	$mpctable__['has_variation'] = false;

	$mpctable__['products'] = array();

	// According to columns.
	$data = array();

	foreach ( $products->posts as $ppost ) {
		$prodid = $ppost->ID;

		// Get product object.
		$product = wc_get_product( $prodid );

		if ( ! $product ) {
			continue;
		}

		$data[ $prodid ] = array(
			'type'              => $product->get_type(),
			'title'             => $product->get_title(),
			'url'               => $product->get_permalink(),
			'desc'              => wp_strip_all_tags( do_shortcode( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ) ),
			'price'             => $product->get_price_html(),
			'sold_individually' => $product->is_sold_individually(),
			'sku'               => $product->get_sku(),
			'stock'             => $product->get_stock_quantity(),
			'stock_status'      => $product->get_stock_status(),
			'terms'             => array(),
			'on_sale'           => $product->is_on_sale(),
		);

		// category.
		$terms = get_the_terms( $prodid, 'product_cat' );
		if ( ! empty( $terms ) ) {
			$ids = array();
			foreach ( $terms as $term ) {
				$ids[] = $term->term_id;
			}
			$data[ $prodid ]['terms'] = $ids;
		}

		// Images.
		$imgid = $product->get_image_id();

		if ( ! empty( $imgid ) ) {
			$data[ $prodid ]['image_id'] = $imgid;
		}

		$thumb = isset( $mpctable__['image_sizes'] ) ? $mpctable__['image_sizes']['thumb'] : 'thumb';
		$full  = isset( $mpctable__['image_sizes'] ) ? $mpctable__['image_sizes']['full'] : 'full';

		if ( $imgid ) {
			$data[ $prodid ]['images'] = array(
				$thumb => wp_get_attachment_image_url( $imgid, $thumb ),
				$full  => wp_get_attachment_image_url( $imgid, $full ),
			);
		} else {
			$data[ $prodid ]['images'] = array(
				$thumb => $mpctable__['default_imgs']['thumb'],
				$full  => $mpctable__['default_imgs']['full'],
			);
		}

		// If this product is checked/selected.
		if ( ( is_array( $mpctable__['attributes']['selected'] ) && in_array( $prodid, $mpctable__['attributes']['selected'], true ) ) || 'all' === $mpctable__['attributes']['selected'] ) {
			$data[ $prodid ]['is_selected'] = true;
		} else {
			$data[ $prodid ]['is_selected'] = false;
		}

		// For variable products only.
		if ( strpos( $data[ $prodid ]['type'], 'variable' ) !== false ) {

			$mpctable__['has_variation'] = true;

			// Variation data - dynamic price handling.
			$variation_data = mpc_get_variation_data( $product );

			// For displaying it frontend, use the following way wc_esc_json( wp_json_encode( $variation_data ) ).
			if ( $variation_data ) {
				$data[ $prodid ]['variation_data'] = $variation_data;
			}

			// default product attributes - if any of them is selected pre-defined.
			$default_attributes = $product->get_default_attributes();

			// product attributes.
			$attributes = $product->get_variation_attributes();

			if ( ! is_array( $attributes ) ) {
				continue;
			}

			// For modified attributes.
			$attributes_ = array();

			// Sort Global Attributes according to backend sequence.
			foreach ( $attributes as $name => $options ) {

				// Sanitize name.
				$name_ = sanitize_title( $name );

				// Check if this is a Global Attributes.
				$is_global = false;
				if ( false !== ( strpos( $name_, 'pa_' ) ) ) {
					$is_global = true;
				}

				// Get all terms under the same Attribute.
				$terms = wc_get_product_terms( $prodid, $name, array( 'fields' => 'all' ) );

				// sort variation attributes.
				if ( $is_global ) {
					$options = mpc_sort_variation_options( $product, $name_, $options, $terms );
				}

				// Modified options variable - for storing additional data.
				$options_ = array();
				foreach ( $options as $option ) {
					$option_ = array();

					if ( is_array( $option ) ) {
						$option_ = array(
							'name'  => $option['name'],
							'value' => $option['slug'],
							'slug'  => $option['slug'],
						);
					} else {
						$option_ = array(
							'name'  => $option,
							'value' => $option,
							'slug'  => $option,
						);
					}

					/**
					 * Check if variation option is in stock
					 *
					 * @param $name | attribute name
					 * @param $option['name'] | attribute option
					 */
					$is_in_stock = mpc_if_variation_in_stock( $product, $name, $option_['slug'] );

					if ( ! $is_in_stock ) {
						continue;
					}

					$option_['name'] = esc_html( apply_filters( 'woocommerce_variation_option_name', $option_['name'], null, $name, $product ) );

					$option_['slug'] = sanitize_title( $option_['slug'] );

					// Check if this option is default.
					if ( isset( $default_attributes[ $name_ ] ) && sanitize_title( $default_attributes[ $name_ ] ) === $option_['slug'] ) {
						$option_['is_selected'] = true;
					} else {
						$option_['is_selected'] = false;
					}

					array_push( $options_, $option_ );
				}

				$attributes_[ $name ] = array(
					'label'   => wc_attribute_label( $name ),
					'options' => $options_,
				);
			}

			if ( $attributes_ ) {
				$data[ $prodid ]['attributes'] = $attributes_;
			}
		} else {
			// Pure price.
			$data[ $prodid ]['price_'] = $product->get_price();

			if ( strpos( $data[ $prodid ]['type'], 'subscription' ) !== false ) {
				// get sign up fee - subscription product type.
				$supfee = (int) get_post_meta( $prodid, '_subscription_sign_up_fee', true );
				if ( '' !== $supfee && ! empty( $supfee ) ) {
					$data[ $prodid ]['price_'] += $supfee;
				}
			}
		}

		// handle 3rd party codes.
		$data[ $prodid ] = apply_filters( 'mpcp_modify_product_data', $data[ $prodid ], $product );
	}

	// update columns list.
	if ( ! $mpctable__['has_variation'] ) {
		$mpctable__['columns_list'] = array_diff( $mpctable__['columns_list'], array( 'wmc_ct_variation' ) );
	}

	// update columns list if no variation exists.
	do_action( 'mpc_final_column_processing' );

	$mpctable__['products'] = $data;
}

/**
 * Get products and populate it in global $mpctable__
 */
function mpc_get_products_() {
	global $mpctable__;

	// get wp_query arguments from shortcode attributes.
	$args               = mpc_get_query_attributes( $mpctable__['attributes'], $mpctable__['paged'] );
	$mpctable__['args'] = $args;

	// remove hooks for nuiscense.
	remove_all_filters( 'pre_get_posts' );
	remove_all_filters( 'posts_orderby' );

	// get products from query.
	$products = new WP_Query( $args );
	wp_reset_postdata();

	// save result attributes for future reference.
	if ( ! empty( $products ) ) {

		// Get all table data.
		mpc_get_table_data_array( $products );

		if ( $mpctable__['attributes']['pagination'] ) {
			$mpctable__['query']['total']    = $products->found_posts;
			$mpctable__['query']['max_page'] = $products->max_num_pages;
		}
	}
}

/**
 * Get products from shortcode | return true | false
 *
 * @param array $atts shortcode attribute.
 */
function mpc_get_products_from_shortcode( $atts ) {
	global $mpctable__;

	// keep original shortcode attributes.
	$mpctable__['attributes__'] = mpc_redefine_boolean_values( $atts );

	// assign to global table data structure.
	$mpctable__['attributes'] = mpc_get_shortcode_attributes( $atts );

	// given table attribute, check code.
	if ( isset( $mpctable__['attributes']['table'] ) && ! empty( $mpctable__['attributes']['table'] ) ) {

		$table_id = (int) $mpctable__['attributes']['table'];

		$mpc_opt_sc = new MPCShortcode();
		$code       = $mpc_opt_sc->get_frontend_shortcode( $table_id, false );

		if ( ! empty( $code ) ) {
			$code = str_replace( '[', '', $code );
			$code = str_replace( ']', '', $code );
			$code = str_replace( 'woo-multi-cart', '', $code );

			$atts = shortcode_parse_atts( $code );

			// keep original shortcode attributes.
			$mpctable__['attributes__'] = mpc_redefine_boolean_values( $atts );

			// assign to global table data structure.
			$mpctable__['attributes'] = mpc_get_shortcode_attributes( $atts );

		} else {
			return; // given table returned nothing.
		}
	}

	// get page.
	if ( isset( $_POST['page'] ) && ! empty( $_POST['page'] ) ) {
		$mpctable__['paged'] = (int) sanitize_key( $_POST['page'] );
	} else {
		$mpctable__['paged'] = 1;
	}

	// Do action to get products data.
	do_action( 'mpc_get_products' );

	// If not products are found - return.
	if ( ! isset( $mpctable__['products'] ) || empty( $mpctable__['products'] ) ) {
		return;
	}

	// If no columns found - return.
	if ( ! isset( $mpctable__['columns_list'] ) || empty( $mpctable__['columns_list'] ) ) {
		return false;
	}

	// modify table data.
	do_action( 'mpc_modify_table_data' );

	return true;
}

/**
 * AJAX table html content loader
 */
function mpc_ajax_table_loader() {
	$r = array( 'status' => '' );

	if ( ! isset( $_POST ) || ( ! isset( $_POST['page'] ) ) ) {
		$r['status'] = 'error';
		$r['msg']    = 'POST - page or atts variable not found.';
	}

	$atts = array(); // shortcode attribute data.
	if ( isset( $_POST['atts'] ) ) {
		$atts = array_map( 'sanitize_text_field', wp_unslash( $_POST['atts'] ) );
	}

	if ( ! mpc_get_products_from_shortcode( $atts ) ) {
		$r['status'] = 'error';
		$r['msg']    = 'POST - page variable not found.';
	}

	if ( 'error' === $r['status'] ) {
		wp_send_json( $r );
	}

	global $mpctable__;

	// no image class.
	$cls = '';
	if ( ! in_array( 'wmc_ct_image', $mpctable__['columns_list'], true ) ) {
		$cls = ' mpc-no-image';
	}

	ob_start();

	// display table body content.
	mpc_display_table();

	$r[] = array(
		'key' => 'table.mpc-wrap',
		'val' => ob_get_clean(),
	);

	ob_start();
	mpc_display_table_pagination_range();
	$r[] = array(
		'key'         => '.mpc-product-range',
		'parent'      => '.mpc-button', // for failsafe - if key element not found add to parent.
		'adding_type' => 'prepend',
		'val'         => ob_get_clean(),
	);

	ob_start();
	mpc_display_pagination_numbers();
	$r[] = array(
		'key'         => '.mpc-pagenumbers',
		'parent'      => '.mpc-inner-pagination',
		'adding_type' => 'prepend',
		'val'         => ob_get_clean(),
	);

	wp_send_json(
		array(
			'mpc_fragments' => $r,
			'original'      => $mpctable__['attributes__'],
			'processed'     => $mpctable__['attributes'],
			'request'       => $_POST,
			'args'          => $mpctable__['args'],
		)
	);
}

/**
 * Ajax add products to cart
 */
function mpc_ajax_add_to_cart() {

	// check ajax add to cart data.
	if ( ! isset( $_POST['mpca_cart_data'] ) ) {
		return;
	}

	// unslash and sanitize array data.
	mpc_handle_add_to_cart( wp_unslash( $_POST['mpca_cart_data'] ), 'ajax' );
}


/**
 * Add "woo-multi-cart" shortcode.
 *
 * @param array $atts shortcode attributes.
 */
function mpc_multicart_shortcode( $atts ) {
	global $mpctable__;

	// if no products found from shortcode return.
	if ( ! mpc_get_products_from_shortcode( $atts ) ) {
		return;
	}

	ob_start();

	require_once apply_filters( 'mpc_template_loader', MPC_PATH . 'assets/php-css/dynamic-styles.php' );

	// // Load main table template file.
	include apply_filters( 'mpc_template_loader', MPC_PATH . 'templates/listing-list.php' );

	$content = ob_get_contents();
	ob_get_clean();

	// load image pop-up template.
	add_action(
		'wp_footer',
		function() {
			mpc_frontend_image_popup();
		}
	);

	return do_shortcode( $content );
}
