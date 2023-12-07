<?php
$cls = new MPCSettings();

global $mpc__;
?>
<form method="post" action="" id="mpcdp_settings_form">
	<div id="mpcdp_settings" class="mpcdp_container">
		<div id="mpcdp_settings_page_header">
			<div id="mpcdp_logo">
				Multiple Products to Cart
			</div>
			<div id="mpcdp_customizer_wrapper">
			</div>
			<div id="mpcdp_toolbar_icons">
				<a class="mpcdp-tippy" target="_blank" href="<?php echo esc_url( $mpc__['plugin']['docs'] ); ?>" data-tooltip="Documentation">
				<span class="tab_icon dashicons dashicons-media-document"></span>
				</a>
				<a class="mpcdp-tippy" target="_blank" href="<?php echo esc_url( $mpc__['plugin']['support'] ); ?>" data-tooltip="Support">
				<span class="tab_icon dashicons dashicons-email"></span>
				</a>
			</div>
		</div>
		<div class="mpcdp_row">
			<div class="col-md-3" id="left-side">
				<div class="mpcdp_settings_sidebar" data-sticky-container="" style="position: relative;">
					<div class="mpcdp_sidebar_tabs">
						<div class="inner-wrapper-sticky">
							<?php $cls->menu(); ?>
							<?php $cls->save_btn(); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6" id="middle-content">
				<div class="mpcdp_settings_content">
					<div id="general" class="hidden mpcdp_settings_tab active" data-tab="general" style="display: block;">
						<?php $cls->settings(); ?>
					</div>
				</div>
			</div>
			<div id="right-side">
				<div class="mpcdp_settings_promo">
					<div id="wfl-promo">
						<?php require MPC_PATH . 'templates/admin/sidebar.php'; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php require MPC_PATH . 'templates/admin/popup.php'; ?>