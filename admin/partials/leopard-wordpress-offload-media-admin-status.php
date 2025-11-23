<?php
/**
 * Admin View: Page - Status
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_tab = ! empty( $_REQUEST['tab'] ) ? sanitize_title( $_REQUEST['tab'] ) : 'status';
$tabs = [
	'status' => esc_html__( 'System status', 'leopard-wordpress-offload-media' )
];
$tabs = apply_filters( 'leopard_admin_status_tabs', $tabs );
?>
<div class="wrap woocommerce">
	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
		<?php
		foreach ( $tabs as $name => $label ) {
			echo '<a href="' . admin_url( 'admin.php?page=leopard_offload_media_scheduled_actions&tab=' . $name ) . '" class="nav-tab ';
			if ( $current_tab == $name ) {
				echo 'nav-tab-active';
			}
			echo '">' . $label . '</a>';
		}
		?>
	</nav>
	<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $current_tab ] ); ?></h1>
	<?php 
		switch($current_tab){
			case "status":
				?>
				<table class="wc_status_table widefat" cellspacing="0" id="status">
					<thead>
						<tr>
							<th colspan="3" data-export-label="WordPress Environment"><h2><?php esc_html_e( 'WordPress environment', 'leopard-wordpress-offload-media' ); ?></h2></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td data-export-label="WP Version"><?php esc_html_e( 'WordPress version', 'leopard-wordpress-offload-media' ); ?>:</td>
							<td><?php echo esc_html(get_bloginfo( 'version' ));?></td>
							<td class="help"><?php echo ( esc_html__( 'The version of WordPress installed on your site.', 'leopard-wordpress-offload-media' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
						</tr>
						<tr>
							<td data-export-label="WP Version"><?php esc_html_e( 'WordPress multisite', 'leopard-wordpress-offload-media' ); ?>:</td>
							<td>
								<?php if ( is_multisite() ) : ?>
									<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
								<?php else : ?>
									<mark class="red">&ndash;</mark>
								<?php endif; ?>
							</td>
							<td class="help"><?php echo ( esc_html__( 'Whether or not you have WordPress Multisite enabled.', 'leopard-wordpress-offload-media' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
						</tr>
						<tr>
							<td data-export-label="WP Debug Mode"><?php esc_html_e( 'WordPress debug mode', 'leopard-wordpress-offload-media' ); ?>:</td>
							<td>
								<?php if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) : ?>
									<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
								<?php else : ?>
									<mark class="red">&ndash;</mark>
								<?php endif; ?>
							</td>
							<td class="help"><?php echo ( esc_html__( 'Displays whether or not WordPress is in Debug Mode.', 'leopard-wordpress-offload-media' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
						</tr>
						<tr>
							<td data-export-label="WP Cron"><?php esc_html_e( 'WordPress cron', 'leopard-wordpress-offload-media' ); ?>:</td>
							<td>
								<?php if ( !( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) ) : ?>
									<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
								<?php else : ?>
									<mark class="red">&ndash;</mark>
								<?php endif; ?>
							</td>
							<td class="help"><?php echo ( esc_html__( 'Displays whether or not WP Cron Jobs are enabled.', 'leopard-wordpress-offload-media' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
						</tr>
						<tr>
							<td data-export-label="WP Version"><?php esc_html_e( 'Leopard Offload Media version', 'leopard-wordpress-offload-media' ); ?>:</td>
							<td><?php echo esc_html(LEOPARD_WORDPRESS_OFFLOAD_MEDIA_VERSION);?></td>
						</tr>

						<tr>
							<td data-export-label="Offloaded"><?php esc_html_e( 'Offloaded files', 'leopard-wordpress-offload-media' ); ?>:</td>
							<td>
								<?php 
								$media_count = leopard_offload_media_count_offloaded();
								echo esc_html($media_count['count']);
								?>
							</td>
						</tr>

						<tr>
							<td data-export-label="WP Cron"><?php esc_html_e( 'Reset scheduled actions', 'leopard-wordpress-offload-media' ); ?>:</td>
							<td>
								<form method="post">
									<input type="submit" class="button-primary" value="<?php esc_html_e('Reset', 'leopard-wordpress-offload-media');?>">
									<input type="hidden" id="nou_leopard_wom_reset_nonce" name="nou_leopard_wom_reset_nonce" value="<?php echo esc_attr(wp_create_nonce('nou_leopard_wom_reset_nonce'));?>">
								</form>
							</td>
						</tr>

					</tbody>
				</table>
				<?php
				break;
			default:
				if ( array_key_exists( $current_tab, $tabs ) && has_action( 'leopard_admin_status_content_' . $current_tab ) ) {
					do_action( 'leopard_admin_status_content_' . $current_tab );
				}
				break;
		}
	?>
</div>
