<?php
if (!defined('ABSPATH')) {exit;}

/**
 * Copy file to cloud Background Process
 *
 * @link       https://themeforest.net/user/nouthemes/portfolio
 * @since      1.0.22
 *
 * @package    Leopard_Wordpress_Offload_Media
 * @subpackage Leopard_Wordpress_Offload_Media/includes
 */

class Leopard_Wordpress_Offload_Media_Scan_Attachments_Process {

	/**
	 * Initiate new background process.
	 */
	public function __construct() {
		add_action( 'nou_leopard_offload_media_cronjob_scan_attachments', [ $this, 'task' ] );
	}

	public function unschedule(){
		try {
			leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_scan_attachments' );
		} catch (\Throwable $th) {}
		return false;
	}

	public function completed(){

		try {
			leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_scan_attachments' );
		} catch (\Throwable $th) {}

		leopard_wordpress_offload_media_scan_attachments_completed();
	}

	/**
	 * Task
	 *
	 * @return mixed
	 */
	public function task() {

		$status = get_option('nou_leopard_offload_media_step_scan_attachments', 0);
		if($status != 1){
			$this->unschedule();
		}

		$scanned = get_option('nou_leopard_offload_media_scanned_attachments');
		if(!is_array($scanned)){
			$scanned = [];
		}

		$args = array( 
			'fields'        	=> 'ids',
			'post_type' 		=> 'attachment',
			'post_status' 		=> 'inherit',
			'posts_per_page' 	=> 100,
			'meta_query' 		=> [
				[
					'key'     => 'nou_leopard_wom_scanned_status',
					'value'   => '1',
					'compare' => 'NOT EXISTS',
				],
			]
		);
		$query = new WP_Query($args);
		$found_posts = $query->found_posts;
		
		if($found_posts == 0){
			
			$this->completed();

			update_option('nou_leopard_offload_media_lasted_scan_attachments', strtotime("today"));
			update_option('nou_leopard_offload_media_step_scan_attachments', 2);

			$total = count($scanned);
			error_log("Scan completed: {$total} attachments.");
			return false;
		}

		foreach ( leopard_wordpress_offload_media_lazy_loop($query) as $post ) {
			ini_set("memory_limit", -1);
			set_time_limit(0);
			$id = get_the_ID();
			$scanned[] = $id;
			update_post_meta($id, 'nou_leopard_wom_scanned_status', 1);
		}
	
		update_option('nou_leopard_offload_media_scanned_attachments', array_unique($scanned));
		update_option('nou_leopard_offload_media_scanned_attachments_copy', array_unique($scanned));

		$scaned = get_option('nou_leopard_offload_media_scaned_pages_attachments');
		update_option('nou_leopard_offload_media_scaned_pages_attachments', ($scaned + 1) );
		update_option('nou_leopard_offload_media_page_scaned_attachments', ($scaned + 1));

		return false;
	}
}
?>