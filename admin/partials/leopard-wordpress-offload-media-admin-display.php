<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://themeforest.net/user/nouthemes/portfolio
 * @since      1.0.0
 *
 * @package    Leopard_Wordpress_Offload_Media
 * @subpackage Leopard_Wordpress_Offload_Media/admin/partials
 */
$tab = 'connectS3';
if(isset($_GET['tab'])){
	$tab = $_GET['tab'];
}
$remove_file_server = get_option('nou_leopard_offload_media_remove_from_server_checkbox');
$action_scan = get_option('nou_leopard_offload_media_action');
$step_scan = get_option('nou_leopard_offload_media_step_scan_attachments', 0);


$default = get_option('nou_leopard_offload_media');
$provider = isset($default['provider']) ? $default['provider'] : 'aws';

?>	
<div class="notice-info notice">
	<p><a class="button-primary" target="_blank" href="<?php echo esc_url('//nouthemes.com/docs/leopard/');?>"><?php esc_html_e('Read the Leopard features!', 'leopard-wordpress-offload-media');?></a></p>
</div>

<div class="wrap" id="leopard-wordpress-offload-media-wrap">
	<h1><?php esc_html_e( 'Leopard Offload Media', 'leopard-wordpress-offload-media' );?></h1>
	
	<?php Leopard_Wordpress_Offload_Media_Messages::show_messages();?>

	<div class="nou_leopard_wom_loading"><?php esc_html_e('Loading', 'leopard-wordpress-offload-media');?>&#8230;</div>

	<div class="col-left">
		<h2 class="nav-tab-wrapper">
		    <a class="nav-tab <?php if($tab == 'connectS3'){echo 'nav-tab-active';}?>" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=connectS3'));?>"><?php esc_html_e('Storage Settings', 'leopard-wordpress-offload-media');?></a>
		    
			<?php $status = get_option('nou_leopard_offload_media_connection_success', 0);?>
		    <?php if($status == 1):?>

		    	<?php $bucket_selected = get_option('nou_leopard_offload_media_connection_bucket_selected_select', '');?>
			    <a class="<?php if(empty($bucket_selected)){echo 'red';}?> nav-tab <?php if($tab == 'generalsettings'){echo 'nav-tab-active';}?>" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=generalsettings'));?>">
			    	<?php esc_html_e('Bucket Settings', 'leopard-wordpress-offload-media');?>
			    	<?php 
			    	if(empty($bucket_selected)){
			    		esc_html_e('(Bucket does not exist)', 'leopard-wordpress-offload-media');
			    	}
			    	?>	
			    </a>

				<?php if ( nou_leopard_offload_media_is_plugin_setup() ) {?>
					<a class="nav-tab <?php if($tab == 'assets'){echo 'nav-tab-active';}?>" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=assets'));?>"><?php esc_html_e('Assets', 'leopard-wordpress-offload-media');?></a>          
					<a class="nav-tab <?php if($tab == 'RewriteUrl'){echo 'nav-tab-active';}?>" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=RewriteUrl'));?>"><?php esc_html_e('URL Rewriting', 'leopard-wordpress-offload-media');?></a>          
					
					<?php if($provider !== 'bunnycdn'):?>
					<a class="nav-tab <?php if($tab == 'cors'){echo 'nav-tab-active';}?>" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=cors'));?>"><?php esc_html_e('CORS', 'leopard-wordpress-offload-media');?></a>         
					<?php endif;?>
					
					<?php if( class_exists('WooCommerce') || class_exists('Easy_Digital_Downloads') ):?>
						<a class="nav-tab <?php if($tab == 'download'){echo 'nav-tab-active';}?>" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=download'));?>"><?php esc_html_e('Download', 'leopard-wordpress-offload-media');?></a>
					<?php endif;?>
					
					<a class="nav-tab <?php if($tab == 'advanced'){echo 'nav-tab-active';}?>" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=advanced'));?>"><?php esc_html_e('Advanced', 'leopard-wordpress-offload-media');?></a>
					<a class="nav-tab <?php if($tab == 'sync'){echo 'nav-tab-active';}?>" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=sync'));?>"><?php esc_html_e('Copy Data', 'leopard-wordpress-offload-media');?></a>
				<?php }?>

			<?php endif;?>     

			
			<a class="nav-tab export-settings" href="<?php echo esc_url(admin_url('admin.php?page=leopard_offload_media&tab=import'));?>"><?php esc_html_e('Import/Export settings', 'leopard-wordpress-offload-media');?></a>     
			
		</h2>
		<form method="post">
			<input type="hidden" id="nou_leopard_wom_settings_nonce" name="nou_leopard_wom_settings_nonce" value="<?php echo esc_attr(wp_create_nonce('nou_leopard_wom_settings_nonce'));?>">
			
			<?php 
			if($tab == 'connectS3'){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-connect.php';
			}

			if($tab == 'generalsettings' && $status == 1){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-general.php';
			}

			if($tab == 'RewriteUrl' && $status == 1){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-url.php';
			}

			if($tab == 'assets' && $status == 1){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-assets.php';
			}

			if($tab == 'cors' && $status == 1){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-cors.php';
			}

			if($tab == 'advanced' && $status == 1){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-advanced.php';
			}

			if($tab == 'import'){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-import.php';
			}

			if($tab == 'download' && $status == 1){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-download.php';
			}

			if($tab == 'sync' && $status == 1){
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/leopard-wordpress-offload-media-admin-settings-sync.php';
			}else{
			?>
			<input data-tab="<?php echo esc_attr($tab);?>" type="submit" id="nou_leopard_wom_settings_submit" class="button-primary" value="<?php esc_html_e('Save Changes', 'leopard-wordpress-offload-media');?>">
			<?php }?>

		</form>
	</div>
	<div class="col-right">
		<?php 
		if(!nou_leopard_offload_media_cache_folder_check()):
		?>
		<div class="card error">
			<h2 class="title">
				<?php esc_html_e('System error!', 'leopard-wordpress-offload-media');?>
			</h2>
			<p><?php printf( _x( 'Please make sure %s is a writable directory.', 'writable directory', 'leopard-wordpress-offload-media' ), LEOPARD_WORDPRESS_OFFLOAD_MEDIA_CACHE_PATH );?></p>
		</div>
		<?php endif;?>
	</div>
</div>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
