<?php

/**
 * Config API
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://themeforest.net/user/nouthemes/portfolio
 * @since      1.0.0
 *
 * @package    Leopard_Wordpress_Offload_Media
 * @subpackage Leopard_Wordpress_Offload_Media/admin/partials
 */
$default = get_option('nou_leopard_offload_media');
$regional = get_option('nou_leopard_offload_media_bucket_regional', 'nyc3');
$provider = isset($default['provider']) ? $default['provider'] : 'aws';

?>
<?php $status = get_option('nou_leopard_offload_media_connection_success', 0);?>
<div id="nou_leopard_wom_connection_status">
	<div>
		<?php if($status == 1):?>
			<p class="nou_leopard_wom_error_accessing_class">
				<img class="nou_leopard_wom_error_accessing_class_img" style="width: 35px;" src="<?php echo esc_url(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_URI.'admin/images/access-ok.png');?>">
				<span class="nou_leopard_wom_error_accessing_class_span"><?php esc_html_e('Connection was successful', 'leopard-wordpress-offload-media');?></span>
			</p>
		<?php else:?>
			<p class="nou_leopard_wom_error_accessing_class">
				<img class="nou_leopard_wom_error_accessing_class_img" style="width: 35px;" src="<?php echo esc_url(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_URI.'admin/images/access-error-logs.png');?>">
				<span class="nou_leopard_wom_error_accessing_class_span"><?php esc_html_e('An error occurred while accessing, the credentials (access key or secret key) are NOT correct', 'leopard-wordpress-offload-media');?></span>
			</p>
		<?php endif;?>
	</div>
</div>

<p class="nou_leopard_wom_admin_parent_wrap">
	<label>
		<span class="nou_leopard_wom_title"><?php esc_html_e('Storage Provider', 'leopard-wordpress-offload-media');?></span>
		<select class="nou_leopard_wom_input_text" name="nou_leopard_wom_connection_provider">
			<?php foreach(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PROVIDER as $key => $cloud ){?>
			<option value="<?php echo esc_attr($key);?>" <?php selected($key, $provider);?>><?php echo esc_html($cloud);?></option>
			<?php }?>
		</select>

		<span class="<?php if($provider != 'DO'){echo 'hidden';}?> conditional show_if_DO"><?php esc_html_e('Region', 'leopard-wordpress-offload-media');?></span>
		<select class="<?php if($provider != 'DO'){echo 'hidden';}?> nou_leopard_wom_input_text conditional show_if_DO" name="nou_leopard_offload_media_bucket_regional">
			<?php foreach(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_DO_REGIONS as $key => $cloud ){?>
			<option value="<?php echo esc_attr($key);?>" <?php selected($key, $regional);?>><?php echo esc_html($cloud);?></option>
			<?php }?>
		</select>
		<span class="nou_leopard_wom_description"><?php esc_html_e('Specify the service you are using for cloud storage.', 'leopard-wordpress-offload-media');?></span>
		<span class="nou_leopard_wom_description"><strong class="red"><?php esc_html_e('Important:', 'leopard-wordpress-offload-media');?></strong> <?php esc_html_e('before change provider, make sure all files has been stored server.', 'leopard-wordpress-offload-media');?></span>
	</label>
</p>

<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_google <?php if($provider != 'google'){echo 'hidden';}?>">
	<label>
		<span class="nou_leopard_wom_title"><?php esc_html_e('Credentials', 'leopard-wordpress-offload-media');?></span>
		<textarea class="nou_leopard_wom_input_text" name="nou_leopard_wom_connection_credentials"><?php echo json_encode(get_option('nou_leopard_offload_media_google_credentials', ''));?></textarea>
		<span class="nou_leopard_wom_description"><?php esc_html_e('Authentication credentials to your application.', 'leopard-wordpress-offload-media');?></span>
	</label>
</p>

<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_cloudflare-r2 show_if_DO show_if_aws show_if_wasabi show_if_bunnycdn <?php if($provider == 'google'){echo 'hidden';}?>">
	<label>
		<span class="nou_leopard_wom_title"><?php esc_html_e('Access key', 'leopard-wordpress-offload-media');?></span>
		<input class="nou_leopard_wom_input_text" type="password" name="nou_leopard_wom_connection_access_key_text" value="<?php echo esc_attr(isset($default['access_key']) ? $default['access_key'] : '');?>">
		<span class="nou_leopard_wom_description"><?php esc_html_e('Set the access key', 'leopard-wordpress-offload-media');?></span>
	</label>
</p>

<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_bunnycdn <?php if($provider !== 'bunnycdn'){echo 'hidden';}?>">

	<label>

		<span class="nou_leopard_wom_title"><?php esc_html_e('Storage key', 'leopard-wordpress-offload-media');?></span>

		<input class="nou_leopard_wom_input_text" type="password" name="nou_leopard_wom_connection_bunny_storage_key" value="<?php echo esc_attr(get_option('nou_leopard_wom_connection_bunny_storage_key', ''));?>">

		<span class="nou_leopard_wom_description">
			<?php esc_html_e('Unfortunately, you can\'t create a storage zone via the api.', 'leopard-wordpress-offload-media');?> <a href="https://bunnycdn.com/dashboard/storagezones" target="_blank"><?php esc_html_e('You will have to manually create a storage zone here.', 'leopard-wordpress-offload-media');?></a>
			<a class="leopard-tooltip" href="<?php echo esc_attr(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_URI . 'admin/images/bunny-storage-key.png');?>" target="_blank">
				<?php esc_html_e('After you have created a storage zone, you can get the api key(or might be labeled "password") of it.', 'leopard-wordpress-offload-media');?>
				<span><img src="<?php echo esc_attr(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_URI . 'admin/images/bunny-storage-key.png');?>"></span>
			</a>
		</span>

	</label>

</p>

<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_bunnycdn <?php if($provider !== 'bunnycdn'){echo 'hidden';}?>">

	<label>

		<span class="nou_leopard_wom_title"><?php esc_html_e('Storage path', 'leopard-wordpress-offload-media');?></span>

		<input class="nou_leopard_wom_input_text" type="text" name="nou_leopard_wom_connection_bunny_storage_path" value="<?php echo esc_attr(get_option('nou_leopard_wom_connection_bunny_storage_path', ''));?>">
		<span class="nou_leopard_wom_description">
			<a class="leopard-tooltip" href="<?php echo esc_attr(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_URI . 'admin/images/bunny-storage-path.png');?>" target="_blank">
				<?php esc_html_e('EX: /leopard/', 'leopard-wordpress-offload-media');?>
				<span><img src="<?php echo esc_attr(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_URI . 'admin/images/bunny-storage-path.png');?>"></span>
			</a>
		</span>
	</label>

</p>

<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_cloudflare-r2 show_if_DO show_if_aws show_if_wasabi <?php if($provider == 'google' || $provider == 'bunnycdn'){echo 'hidden';}?>">

	<label>

		<span class="nou_leopard_wom_title"><?php esc_html_e('Secret access key', 'leopard-wordpress-offload-media');?></span>

		<input class="nou_leopard_wom_input_text" type="password" name="nou_leopard_wom_connection_secret_access_key_text" value="<?php echo esc_attr(isset($default['secret_access_key']) ? $default['secret_access_key'] : '');?>">

		<span class="nou_leopard_wom_description"><?php esc_html_e('Set the secret access key', 'leopard-wordpress-offload-media');?></span>

	</label>

</p>

<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_google <?php if($provider != 'google'){echo 'hidden';}?>">

	<label>

    <span class="nou_leopard_wom_description" style="margin-top: 10px;"><?php esc_html_e('If you don\'t know where to search for your Google Cloud Storage credentials,', 'leopard-wordpress-offload-media');?> <a href="https://cloud.google.com/storage/docs/reference/libraries#setting_up_authentication" target="_blank"><?php esc_html_e('you can find them here', 'leopard-wordpress-offload-media');?></a>

    </span>

	</label>

</p>

<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_aws <?php if($provider != 'aws'){echo 'hidden';}?>">

	<label>

    <span class="nou_leopard_wom_description" style="margin-top: 10px;"><?php esc_html_e('If you don\'t know where to search for your AWS S3 credentials,', 'leopard-wordpress-offload-media');?> <a href="https://aws.amazon.com/blogs/security/wheres-my-secret-access-key/" target="_blank"><?php esc_html_e('you can find them here', 'leopard-wordpress-offload-media');?></a>

    </span>

	</label>

</p>

<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_wasabi <?php if($provider != 'wasabi'){echo 'hidden';}?>">

	<label>

    <span class="nou_leopard_wom_description" style="margin-top: 10px;"><?php esc_html_e('If you don\'t know where to search for your Wasabi credentials,', 'leopard-wordpress-offload-media');?> <a href="https://wasabi-support.zendesk.com/hc/en-us/articles/360019677192-Creating-a-Root-Access-Key-and-Secret-Key" target="_blank"><?php esc_html_e('you can find them here', 'leopard-wordpress-offload-media');?></a>

    </span>

	</label>

</p>


<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_cloudflare-r2 <?php if($provider != 'cloudflare-r2'){echo 'hidden';}?>">

	<label>

		<span class="nou_leopard_wom_title"><?php esc_html_e('Account ID', 'leopard-wordpress-offload-media');?></span>

		<input class="nou_leopard_wom_input_text" type="text" name="nou_leopard_wom_connection_r2_account_id" value="<?php echo esc_attr(get_option('nou_leopard_wom_connection_r2_account_id', ''));?>">
		<span class="nou_leopard_wom_description"><?php esc_html_e('Set the Account ID', 'leopard-wordpress-offload-media');?></span>

	</label>

</p>
<p class="nou_leopard_wom_admin_parent_wrap conditional show_if_cloudflare-r2 <?php if($provider != 'cloudflare-r2'){echo 'hidden';}?>">

	<label>

		<span class="nou_leopard_wom_title"><?php esc_html_e('Public Bucket URL', 'leopard-wordpress-offload-media');?></span>

		<input class="nou_leopard_wom_input_text" type="text" name="nou_leopard_wom_connection_r2_bucket_url" value="<?php echo esc_attr(get_option('nou_leopard_wom_connection_r2_bucket_url', ''));?>">
		<span class="nou_leopard_wom_description"><?php esc_html_e('Set the Public Bucket URL', 'leopard-wordpress-offload-media');?></span>

	</label>

</p>