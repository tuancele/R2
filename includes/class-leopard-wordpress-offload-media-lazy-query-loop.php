<?php
if (!defined('ABSPATH')) {exit;}
/**
 * Generator the WordPress loop.
 *
 * @link       https://themeforest.net/user/nouthemes/portfolio
 * @since      1.0.0
 *
 * @package    Leopard_Wordpress_Offload_Media
 * @subpackage Leopard_Wordpress_Offload_Media/includes
 * @author     Nouthemes <nguyenvanqui89@gmail.com>
 */

/**
 * Simplifies the WordPress loop.
 *
 * @param WP_Query|WP_Post[] $iterable
 *
 * @return Generator
 */
class Leopard_Wordpress_Offload_Media_Lazy_Query_Loop {

	public static function generator($iterable = null){
		if ( null === $iterable ) {
			$iterable = $GLOBALS['wp_query'];
		}
	
		$posts = $iterable;
		if ( is_object( $iterable ) && property_exists( $iterable, 'posts' ) ) {
			$posts = $iterable->posts;
		}
	
		if ( ! is_array( $posts ) ) {
			throw new \InvalidArgumentException( sprintf( esc_html__('Expected an array, received %s instead', 'leopard-wordpress-offload-media'), gettype( $posts ) ) );
		}
	
		global $post;
	
		// Save the global post object so we can restore it later
		$save_post = $post;
	
		try {
	
			foreach ( $posts as $post ) {
				setup_postdata( $post );
				yield $post;
			}
	
		} finally {
			wp_reset_postdata();
	
			// Restore the global post object
			$post = $save_post;
		}
	}
}