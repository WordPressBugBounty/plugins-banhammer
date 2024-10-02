<?php // Reset Settings

if (!defined('ABSPATH')) exit;

function banhammer_admin_notices() {
	
	$screen_id = banhammer_get_current_screen_id();
	
	if ($screen_id === 'toplevel_page_banhammer') {
		
		if (isset($_GET['banhammer-reset-options'])) {
			
			if ($_GET['banhammer-reset-options'] === 'true') : ?>
				
				<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('Default options restored.', 'banhammer'); ?></strong></p></div>
				
			<?php else : ?>
				
				<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('No changes made to options.', 'banhammer'); ?></strong></p></div>
				
			<?php endif;
			
		}
		
		if (!banhammer_check_date_expired() && !banhammer_dismiss_notice_check()) {
			
			?>
			
			<div class="notice notice-success notice-custom">
				<p>
					<strong><?php esc_html_e('Pro Plugin Sale!', 'banhammer'); ?></strong> 
					<?php esc_html_e('Buy one get one FREE with code', 'banhammer'); ?> <code>BOGO24</code>, 
					<?php esc_html_e('or take 30% off with code', 'banhammer'); ?> <code>SUPER24</code> 
					⭐ <a class="notice-link" target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/super-summer-sale/"><?php esc_html_e('Get&nbsp;plugins&nbsp;&raquo;', 'banhammer'); ?></a> 
					<?php echo banhammer_dismiss_notice_link(); ?>
				</p>
			</div>
			
			<?php
			
		}
		
	} elseif ($screen_id === 'banhammer_page_banhammer-tower') {
		
		if (isset($_GET['banhammer-add-target'])) {
			
			if ($_GET['banhammer-add-target'] === 'true') : ?>
				
				<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('Target added to Tower.', 'banhammer'); ?></strong></p></div>
				
			<?php else : ?>
				
				<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('Target already exists in Tower.', 'banhammer'); ?></strong></p></div>
				
			<?php endif;
			
		}
		
	}
	
}

//

function banhammer_dismiss_notice_activate() {
	
	delete_option('banhammer-dismiss-notice');
	
}

function banhammer_dismiss_notice_version() {
	
	$version_current = BANHAMMER_VERSION;
	
	$version_previous = get_option('banhammer-dismiss-notice');
	
	$version_previous = ($version_previous) ? $version_previous : $version_current;
	
	if (version_compare($version_current, $version_previous, '>')) {
		
		delete_option('banhammer-dismiss-notice');
		
	}
	
}

function banhammer_dismiss_notice_check() {
	
	$check = get_option('banhammer-dismiss-notice');
	
	return ($check) ? true : false;
	
}

function banhammer_dismiss_notice_save() {
	
	if (isset($_GET['dismiss-notice-verify']) && wp_verify_nonce($_GET['dismiss-notice-verify'], 'banhammer_dismiss_notice')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$result = update_option('banhammer-dismiss-notice', BANHAMMER_VERSION, false);
		
		$result = $result ? 'true' : 'false';
		
		$location = admin_url('admin.php?page=banhammer&dismiss-notice='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}

function banhammer_dismiss_notice_link() {
	
	$nonce = wp_create_nonce('banhammer_dismiss_notice');
	
	$href  = add_query_arg(array('dismiss-notice-verify' => $nonce), admin_url('admin.php?page=banhammer'));
	
	$label = esc_html__('Dismiss', 'banhammer');
	
	return '<a class="banhammer-dismiss-notice" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function banhammer_check_date_expired() {
	
	$expires = apply_filters('banhammer_check_date_expired', '2024-09-22');
	
	return (new DateTime() > new DateTime($expires)) ? true : false;
	
}

//

function banhammer_reset_options() {
	
	if (isset($_GET['banhammer-reset-options']) && wp_verify_nonce($_GET['banhammer-reset-options'], 'banhammer_reset_options')) {
		
		if (!current_user_can('manage_options')) exit;
		
		global $BanhammerWP;
		
		$armory = $BanhammerWP->armory();
		
		$options = $BanhammerWP->options();
		
		$armory_update = update_option('banhammer_armory', $armory);
		
		$options_update = update_option('banhammer_settings', $options);
		
		$result = 'false';
		
		if ($armory_update || $options_update) $result = 'true';
		
		$location = admin_url('admin.php?page=banhammer&banhammer-reset-options='. $result);
		
		wp_redirect(esc_url_raw($location));
		
		exit;
		
	}
	
}

function banhammer_remove_query_args($params) {
	
	if (!is_null($params)) $params[] = 'banhammer-reset-options';
	
	return $params;
	
}
