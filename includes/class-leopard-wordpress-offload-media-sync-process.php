<?php
if (!defined('ABSPATH')) {exit;}

/**
 * Sync Background Process
 *
 * @link       https://themeforest.net/user/nouthemes/portfolio
 * @since      2.0
 *
 * @package    Leopard_Wordpress_Offload_Media
 * @subpackage Leopard_Wordpress_Offload_Media/includes
 */

class Leopard_Wordpress_Offload_Media_Sync_Process {

	/**
	 * Initiate new background process.
	 */
	public function __construct() {
		add_action( 'nou_leopard_offload_media_cronjob_sync_between_cloud', [ $this, 'task' ] );
	}
	
	public function task() {

		if(!leopard_wordpress_offload_media_cronjob_timed()){
			return false;
		}

		$status = get_option('nou_leopard_offload_media_synced_status', 0);
		if($status == 0){
			try {
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_sync_between_cloud' );
			} catch (\Throwable $th) {
				error_log($th->getMessage());
			}
			return false;
		}

		$sync = new Leopard_Wordpress_Offload_Media_Sync();
		$cacheKey = $sync->getCacheKey();
		$cacheData = $sync->getCacheData();
		if(count($cacheData) == 0){
			$this->complete();
		}

		$items = $this->get_cache_objects("{$cacheKey}_copy");
		if(!is_array($items)){
			return false;
		}

		if(count($items) == 0){
			$this->complete();
		}

		$item = $items[0];

		if(!is_array($item)){
			return false;
		}

		if(!isset($item['source']) || !isset($item['key'])){
			return false;
		}

		error_log("Start sync data {$item['source']}");

		array_splice($items, 0, 1);
		$this->set_cache_objects("{$cacheKey}_copy", $items);

		$total_synced = $this->get_cache_objects("{$cacheKey}_synced_data");
		if(!is_array($total_synced)){
			$total_synced = [];
		}

		$url = $sync->sync($item);

		error_log("End sync data {$item['source']}");

		if(!in_array($item['key'], $total_synced)){
			$total_synced[] = $item['key'];
			$this->set_cache_objects("{$cacheKey}_synced_data", $total_synced);
		}

		if(count($items) == 0){
			$this->complete();
		}
		
		return false;
	}

	public function complete() {
		
		update_option('nou_leopard_offload_media_sync_data', 1);
		update_option('nou_leopard_offload_media_synced_status', 0);

		try{
			$sendEmail = get_option('nou_leopard_offload_media_send_email_task', 'on');
			if(!empty($sendEmail)){
				wp_mail( 
					get_option('admin_email'), 
					esc_html__('Leopard Offload Media Synchronize', 'leopard-wordpress-offload-media'), 
					esc_html__('Leopard Offload Media Synchronize: process has been completed.', 'leopard-wordpress-offload-media') 
				);
			}
		} catch (Exception $e){
			error_log('wp_mail send failed.');
		}

		try {
			if ( function_exists( 'leopard_wordpress_offload_media_unschedule_action' ) ) {
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_sync_between_cloud' );
			}
		} catch (\Throwable $th) {}

		return false;
	}

	private function get_cache_objects($key)
	{
		return leopard_wordpress_offload_media_get_sync_objects($key);
	}

	private function set_cache_objects($key, $objects)
	{
		return leopard_wordpress_offload_media_set_sync_objects($key, $objects);
	}
}
?>