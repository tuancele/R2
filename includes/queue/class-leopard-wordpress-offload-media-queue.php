<?php
/**
 * Queue
 *
 * @version 1.0.31
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Queue
 *
 * Singleton for managing the queue instance.
 *
 * @version 1.0.31
 */
class Leopard_Wordpress_Offload_Media_Queue {

	/**
	 * The single instance of the queue.
	 *
	 * @var Leopard_Wordpress_Offload_Media_Queue_Interface|null
	 */
	protected static $instance = null;

	/**
	 * The default queue class to initialize
	 *
	 * @var string
	 */
	protected static $default_cass = 'Leopard_Wordpress_Offload_Media_Action_Queue';

	/**
	 * Single instance of Leopard_Wordpress_Offload_Media_Queue_Interface
	 *
	 * @return Leopard_Wordpress_Offload_Media_Queue_Interface
	 */
	final public static function instance() {

		if ( is_null( self::$instance ) ) {
			$class          = self::get_class();
			self::$instance = new $class();
			self::$instance = self::validate_instance( self::$instance );
		}
		return self::$instance;
	}

	/**
	 * Get class to instantiate
	 *
	 * And make sure 3rd party code has the chance to attach a custom queue class.
	 *
	 * @return string
	 */
	protected static function get_class() {
		if ( ! did_action( 'plugins_loaded' ) ) {
			nou_leopard_offload_media_doing_it_wrong( __FUNCTION__, __( 'This function should not be called before plugins_loaded.', 'leopard-wordpress-offload-media' ), '3.5.0' );
		}

		return apply_filters( 'leopard_queue_class', self::$default_cass );
	}

	/**
	 * Enforce a Leopard_Wordpress_Offload_Media_Queue_Interface
	 *
	 * @param Leopard_Wordpress_Offload_Media_Queue_Interface $instance Instance class.
	 * @return Leopard_Wordpress_Offload_Media_Queue_Interface
	 */
	protected static function validate_instance( $instance ) {
		if ( false === ( $instance instanceof Leopard_Wordpress_Offload_Media_Queue_Interface ) ) {
			$default_class = self::$default_cass;
			/* translators: %s: Default class name */
			nou_leopard_offload_media_doing_it_wrong( __FUNCTION__, sprintf( __( 'The class attached to the "leopard_queue_class" does not implement the Leopard_Wordpress_Offload_Media_Queue_Interface interface. The default %s class will be used instead.', 'leopard-wordpress-offload-media' ), $default_class ), '3.5.0' );
			$instance = new $default_class();
		}

		return $instance;
	}
}
