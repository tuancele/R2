
<style>
    #nou_leopard_wom_settings_submit{
        display: none;
    }
</style>
<p class="nou_leopard_wom_admin_parent_wrap wom_import">
	<label>
		<span class="nou_leopard_wom_title"><?php esc_html_e('Import settings', 'leopard-wordpress-offload-media');?></span>
		<textarea class="nou_leopard_wom_input_text" id="nou_leopard_wom_import_content" name="nou_leopard_wom_import_json"></textarea>
        <input disabled type="button" id="nou_leopard_wom_import" class="button-primary" value="<?php esc_html_e('Import', 'leopard-wordpress-offload-media');?>">
		<span class="nou_leopard_wom_description"><?php esc_html_e('Enter exported json file content.', 'leopard-wordpress-offload-media');?></span>
	</label>
</p>

<?php $status = get_option('nou_leopard_offload_media_connection_success', 0);?>
<?php if($status == 1):?>
<p class="nou_leopard_wom_admin_parent_wrap">
	<label>
        <span class="nou_leopard_wom_title"><?php esc_html_e('Export settings', 'leopard-wordpress-offload-media');?></span>
        <a id="downloadAnchorElem" style="display:none"></a>
		<input type="button" id="nou_leopard_wom_export" class="button-primary" value="<?php esc_html_e('Export json file', 'leopard-wordpress-offload-media');?>">
	</label>
</p>
<?php endif;?>