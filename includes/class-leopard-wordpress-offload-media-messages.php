<?php
if (!defined('ABSPATH')) {exit;}

class Leopard_Wordpress_Offload_Media_Messages {

    /**
	 * Error messages.
	 *
	 * @var array
	 */
	private static $errors = array();

	/**
	 * Update messages.
	 *
	 * @var array
	 */
	private static $messages = array();
    
    /**
	 * Add a message.
	 *
	 * @param string $text Message.
	 */
	public static function add_message( $text ) {
		self::$messages[] = esc_html($text);
	}

	/**
	 * remove a message.
	 *
	 * @param string $text Message.
	 */
	public static function remove_message() {
		self::$messages = [];
	}

	/**
	 * Add an error.
	 *
	 * @param string $text Message.
	 */
	public static function add_error( $text ) {
		self::$errors[] = esc_html($text);
	}

	/**
	 * Remove error.
	 *
	 * @param string $text Message.
	 */
	public static function remove_error() {
		self::$errors = [];
    }
    

	/**
	 * Output messages + errors.
	 */
	public static function show_messages() {
		if ( count( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( count( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}else{
			if(isset($_POST['nou_leopard_wom_settings_nonce'])){
				echo '
				<div class="updated settings-error notice is-dismissible">
					<p><strong>'. esc_html__( 'Settings saved.', 'leopard-wordpress-offload-media' ) .'</strong></p>
				</div>
				';
			}
		}
	}
}
