<?php
if (!defined('ABSPATH')) {exit;}

/**
 * Remove files bucket Background Process
 *
 * @link       https://themeforest.net/user/nouthemes/portfolio
 * @since      2.0
 *
 * @package    Leopard_Wordpress_Offload_Media
 * @subpackage Leopard_Wordpress_Offload_Media/includes
 */

class Leopard_Wordpress_Offload_Media_Remove_Files_Bucket_Process extends Leopard_Wordpress_Offload_Media_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'remove_files_from_bucket';

	/**
	 * Initiate new background process.
	 */
	public function __construct() {
		if(!$this->check_action()){
			$this->unschedule();
		}else{
			add_action( 'nou_leopard_offload_media_cronjob_remove_files_from_bucket', [ $this, 'task' ] );
		}
	}

	public function check_action(){
		$action_scan = get_option('nou_leopard_offload_media_action');
		if($action_scan == 'remove_files_from_bucket'){
			return true;
		}

		return false;
	}

	public function unschedule(){
		if ( function_exists( 'as_next_scheduled_action' ) ) {
			leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_remove_files_from_bucket' );
		}

		return false;
	}

	public function clearScheduled()
	{
		try {
			if ( function_exists( 'as_next_scheduled_action' ) ) {
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_scan_attachments' );
			}
		} catch (\Throwable $th) {}

		try {
			if ( function_exists( 'as_next_scheduled_action' ) ) {
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_remove_files_from_server' );
			}
		} catch (\Throwable $th) {}

		try {
			if ( function_exists( 'as_next_scheduled_action' ) ) {
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_copy_attachments_to_cloud' );
			}
		} catch (\Throwable $th) {}

		try {
			if ( function_exists( 'as_next_scheduled_action' ) ) {
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_download_files_from_bucket' );
			}
		} catch (\Throwable $th) {}
	}

	/**
	 * Process items chunk.
	 *
	 * @param string $source_type
	 * @param array  $source_ids
	 * @param int    $blog_id
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	protected function process_items_chunk( $source_type, $source_ids, $blog_id ) {
		$processed = [];

		foreach ( $source_ids as $source_id ) {
			leopard_offload_media_remove_from_s3_function($source_id);
		}

		return $processed;
	}

	/**
	 * Task
	 * 
	 * @return mixed
	 */
	public function task() {

		$this->clearScheduled();

		if( !$this->can_run() ){
			return false;
		}

		$media_count = leopard_offload_media_get_media_counts();
		if($media_count['cloud_removed'] >= $media_count['total']){
			$this->complete();
		}

		$this->lock_process();

		try {
			$blog_id = get_current_blog_id();
			$source_type_classes = leopard_offload_media_get_source_type_classes();
			foreach($source_type_classes as $source_type => $class){
				$items = leopard_offload_media_items_remove_file_cloud($source_type, $this->limit, false, false);
				$chunks = array_chunk( $items, $this->chunk );
				foreach ( $chunks as $chunk ) {
					try {
						$this->process_items_chunk($source_type, $chunk, $blog_id);
					} catch (\Throwable $th) {
						error_log("Error remove_files_from_bucket: ". $th->getMessage());
					}
				}
			}	
		} catch (\Throwable $th) {
			error_log("Error remove_files_from_bucket: ". $th->getMessage());
		}

		$this->unlock_process();

		return false;
	}
}
?>