<?php

/**
 * Class ActionScheduler_wpPostStore_PostTypeRegistrar
 * @codeCoverageIgnore
 */
class ActionScheduler_wpPostStore_PostTypeRegistrar {
	public function register() {
		register_post_type( ActionScheduler_wpPostStore::POST_TYPE, $this->post_type_args() );
	}

	/**
	 * Build the args array for the post type definition
	 *
	 * @return array
	 */
	protected function post_type_args() {
		$args = array(
			'label' => __( 'Scheduled Actions', 'leopard-wordpress-offload-media' ),
			'description' => __( 'Scheduled actions are hooks triggered on a cetain date and time.', 'leopard-wordpress-offload-media' ),
			'public' => false,
			'map_meta_cap' => true,
			'hierarchical' => false,
			'supports' => array('title', 'editor','comments'),
			'rewrite' => false,
			'query_var' => false,
			'can_export' => true,
			'ep_mask' => EP_NONE,
			'labels' => array(
				'name' => __( 'Scheduled Actions', 'leopard-wordpress-offload-media' ),
				'singular_name' => __( 'Scheduled Action', 'leopard-wordpress-offload-media' ),
				'menu_name' => _x( 'Scheduled Actions', 'Admin menu name', 'leopard-wordpress-offload-media' ),
				'add_new' => __( 'Add', 'leopard-wordpress-offload-media' ),
				'add_new_item' => __( 'Add New Scheduled Action', 'leopard-wordpress-offload-media' ),
				'edit' => __( 'Edit', 'leopard-wordpress-offload-media' ),
				'edit_item' => __( 'Edit Scheduled Action', 'leopard-wordpress-offload-media' ),
				'new_item' => __( 'New Scheduled Action', 'leopard-wordpress-offload-media' ),
				'view' => __( 'View Action', 'leopard-wordpress-offload-media' ),
				'view_item' => __( 'View Action', 'leopard-wordpress-offload-media' ),
				'search_items' => __( 'Search Scheduled Actions', 'leopard-wordpress-offload-media' ),
				'not_found' => __( 'No actions found', 'leopard-wordpress-offload-media' ),
				'not_found_in_trash' => __( 'No actions found in trash', 'leopard-wordpress-offload-media' ),
			),
		);

		$args = apply_filters('action_scheduler_post_type_args', $args);
		return $args;
	}
}
 