
<input type="hidden" name="nou_leopard_offload_media_download_tab" value="1">
<style>#nou_leopard_wom_settings_submit{display:none;}</style>
<div id="change_links_download_header">
    <h3><?php esc_html_e('Change all links download from server to cloud.', 'leopard-wordpress-offload-media');?></h3>
    <input type="button" id="nou_leopard_wom_change_links_download" class="button-primary" value="<?php esc_html_e('Change all links now', 'leopard-wordpress-offload-media');?>">
</div>
<div id="change_links_download_content" class="hidden">
    <h3><?php esc_html_e('Please sit tight while we change your links. Do not refresh the page.', 'leopard-wordpress-offload-media');?></h3>
    <p><img src="<?php echo esc_url( LEOPARD_WORDPRESS_OFFLOAD_MEDIA_PLUGIN_URI . 'admin/images/importing.svg' ); ?>" alt="<?php esc_attr_e( 'Importing animation', 'leopard-wordpress-offload-media' ); ?>"></p>
</div>
<div id="change_links_download_footer" class="hidden">
    <h3><?php esc_html_e('Links change completed!', 'leopard-wordpress-offload-media');?></h3>
    <p><?php esc_html_e( 'Congrats, your links was changed successfully.' , 'leopard-wordpress-offload-media' ); ?></p>
</div>