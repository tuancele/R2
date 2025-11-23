<?php

class Leopard_Wordpress_Offload_Media_Remove_Provider_Handler extends Leopard_Wordpress_Offload_Media_Item_Handler {
	/**
	 * @var string
	 */
	protected static $item_handler_key = 'remove-provider';

	/**
	 * The default options that should be used if none supplied.
	 *
	 * @return array
	 */
	public static function default_options() {
		return array(
			'object_keys'     => array(),
			'offloaded_files' => array(),
		);
	}

	/**
	 * Create manifest for removal from provider.
	 *
	 * @param Item  $leopard_item
	 * @param array $options
	 *
	 * @return Manifest|WP_Error
	 */
	protected function pre_handle( Leopard_Wordpress_Offload_Media_Item $leopard_item, array $options ) {
		$manifest = new Leopard_Wordpress_Offload_Media_Manifest();
		$paths    = array();

		if ( ! empty( $options['object_keys'] ) && ! is_array( $options['object_keys'] ) ) {
			return $this->return_handler_error( __( 'Invalid object_keys option provided.', 'leopard-wordpress-offload-media' ) );
		}

		if ( ! empty( $options['offloaded_files'] ) && ! is_array( $options['offloaded_files'] ) ) {
			return $this->return_handler_error( __( 'Invalid offloaded_files option provided.', 'leopard-wordpress-offload-media' ) );
		}

		if ( ! empty( $options['object_keys'] ) && ! empty( $options['offloaded_files'] ) ) {
			return $this->return_handler_error( __( 'Providing both object_keys and offloaded_files options is not supported.', 'leopard-wordpress-offload-media' ) );
		}

		if ( empty( $options['offloaded_files'] ) ) {
			foreach ( $leopard_item->objects() as $object_key => $object ) {
				if ( 0 < count( $options['object_keys'] ) && ! in_array( $object_key, $options['object_keys'] ) ) {
					continue;
				}
				$paths[ $object_key ] = $leopard_item->full_source_path( $object_key );
			}
		} else {
			foreach ( $options['offloaded_files'] as $filename => $object ) {
				$paths[ $filename ] = $leopard_item->full_source_path_for_filename( $filename );
			}
		}

		/**
		 * Filters array of source files before being removed from provider.
		 *
		 * @param array $paths       Array of local paths to be removed from provider
		 * @param Item  $leopard_item  The Item object
		 * @param array $item_source The item source descriptor array
		 */
		$paths = apply_filters( 'leopard_remove_source_files_from_provider', $paths, $leopard_item, $leopard_item->get_item_source_array() );
		$paths = array_unique( $paths );

		// Remove local source paths that other items may have offloaded.
		$paths = $leopard_item->remove_duplicate_paths( $leopard_item, $paths );

		// Nothing to do, shortcut out.
		if ( empty( $paths ) ) {
			return $manifest;
		}

		foreach ( $paths as $object_key => $path ) {
			$manifest->objects[] = array(
				'Key' => $leopard_item->source_path( $object_key ),
			);
		}

		return $manifest;
	}

	/**
	 * Delete provider objects described in the manifest object array
	 *
	 * @param Item     $leopard_item
	 * @param Manifest $manifest
	 * @param array    $options
	 *
	 * @return bool|WP_Error
	 */
	protected function handle_item( Leopard_Wordpress_Offload_Media_Item $leopard_item, Leopard_Wordpress_Offload_Media_Manifest $manifest, array $options ) {
		try{
			list( $aws_s3_client, $Bucket, $Region, $array_files ) = leopard_offload_media_aws_array_media_actions_function( $leopard_item->source_id() );
			$aws_s3_client->deleteObject_nou( $Bucket, $Region, $array_files );
		}catch(Exception $e){
			$error_msg = sprintf( __( 'Error removing files from bucket: %s', 'leopard-wordpress-offload-media' ), $e->getMessage() );
			return false;
		}

		return true;
	}

	/**
	 * Perform post handle tasks.
	 *
	 * @param Item     $leopard_item
	 * @param Manifest $manifest
	 * @param array    $options
	 *
	 * @return bool
	 */
	protected function post_handle( Leopard_Wordpress_Offload_Media_Item $leopard_item, Leopard_Wordpress_Offload_Media_Manifest $manifest, array $options ) {
		return true;
	}
}