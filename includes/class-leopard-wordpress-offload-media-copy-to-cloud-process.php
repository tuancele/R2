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

class Leopard_Wordpress_Offload_Media_Copy_To_Cloud_Process extends Leopard_Wordpress_Offload_Media_Background_Process{

	/**
	 * @var string
	 */
	protected $action = 'copy_attachments_to_cloud';

	/**
	 * @var int
	 */
	private $offloaded = 0;

	/**
	 * Initiate new background process.
	 */
	public function __construct() {
		if(!$this->check_action()){
			$this->unschedule();
		}else{
			add_action( 'nou_leopard_offload_media_cronjob_copy_attachments_to_cloud', [ $this, 'task' ] );
		}
	}

	public function check_action(){
		$action_scan = get_option('nou_leopard_offload_media_action');
		if($action_scan == 'copy_files_to_bucket'){
			return true;
		}

		return false;
	}

	public function unschedule(){
		try {
			if ( function_exists( 'as_next_scheduled_action' ) ) {
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_copy_attachments_to_cloud' );
			}
		} catch (\Throwable $th) {}

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
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_download_files_from_bucket' );
			}
		} catch (\Throwable $th) {}

		try {
			if ( function_exists( 'as_next_scheduled_action' ) ) {
				leopard_wordpress_offload_media_unschedule_action( 'nou_leopard_offload_media_cronjob_remove_files_from_bucket' );
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
		$processed = array();

		foreach ( $source_ids as $source_id ) {
			
			try {
				if ( $this->handle_item( $source_type, $source_id, $blog_id ) ) {
					$this->offloaded++;
				}
			} catch (Exception $e) {
				error_log("Error copy_attachments_to_cloud: ". $e->getMessage());
			}

			// Whether actually offloaded or not, we've processed the item.
			$processed[] = $source_id;
		}

		return $processed;
	}

	/**
	 * Upload the item to provider.
	 *
	 * @param string $source_type
	 * @param int    $source_id
	 * @param int    $blog_id
	 *
	 * @return bool
	 * @throws Exception
	 */
	protected function handle_item( $source_type, $source_id, $blog_id ) {
		return leopard_offload_media_copy_to_s3_function( $source_id );
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
		if($media_count['counts_missing_source_ids'] == 0){
			if($media_count['offloaded'] >= $media_count['total']){
				$this->complete();
			}
		}

		$this->lock_process();

		try {
			$blog_id = get_current_blog_id();
			$source_type_classes = leopard_offload_media_get_source_type_classes();
			foreach($source_type_classes as $source_type => $class){
				$items = $class::get_missing_source_ids(null, $this->limit, false);
				$chunks = array_chunk( $items, $this->chunk );
				foreach ( $chunks as $chunk ) {
					try {
						$this->process_items_chunk($source_type, $chunk, $blog_id);
					} catch (\Throwable $th) {
						error_log("Error copy_attachments_to_cloud: ". $th->getMessage());
					}
				}
			}
		} catch (\Throwable $th) {
			error_log("Error copy_attachments_to_cloud: ". $th->getMessage());
		}

		$this->unlock_process();

		return false;
	}
}
?>