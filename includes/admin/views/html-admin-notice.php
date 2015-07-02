<?php
/**
 * Compi Admin Notice Templates
 *
 * @since    1.0.0
 * @author wpdots
 * @category Admin
 * @package  Compi
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( 'install' === $notice ) {

	$submit_btn_url = add_query_arg( 'install_compi_pages', 'true', admin_url( 'admin.php?page=' . COMPI_PAGE . '-settings' ) );
	$submit_btn_text = sprintf( __( 'Install %s Pages', COMPI_TEXT_DOMAIN ), Compi()->name );
	$skip_btn_url = add_query_arg( 'skip_install_compi_pages', 'true', admin_url( 'admin.php?page=' . COMPI_PAGE . '-settings' ) );
	$skip_btn_text = __( 'Skip setup', COMPI_TEXT_DOMAIN ); ?>

	<div id="message" class="updated compi-message">

		<p><?php echo sprintf( __( '<strong>Welcome to %s</strong> &#8211; You\'re almost ready to start using this plugin :)', COMPI_TEXT_DOMAIN ), Compi()->name ); ?></p>

		<p class="submit">
			<a href="<?php echo $submit_btn_url; ?>" class="button-primary"><?php echo $submit_btn_text; ?></a>
			<a class="skip button-primary" href="<?php echo $skip_btn_url; ?>"><?php echo $skip_btn_text; ?></a>
		</p>

	</div>


<?php } elseif ( 'update' === $notice ) {

	$submit_btn_url = add_query_arg( 'do_update_compi', 'true', admin_url( 'admin.php?page=' . COMPI_PAGE . '-settings' ) );
	$submit_btn_text = __( 'Run the updater', COMPI_TEXT_DOMAIN );

	?>

	<div id="message" class="updated compi-message">
		<p><?php echo sprintf( __( '<strong>%s Data Update Required</strong> &#8211; We just need to update your install to the latest version', COMPI_TEXT_DOMAIN ), Compi()->name ); ?></p>

		<p class="submit">
			<a href="<?php echo $submit_btn_url; ?>" class="compi-update-now button-primary"><?php echo $submit_btn_text; ?></a>
		</p>
	</div>
	<script type="text/javascript">
		jQuery('.compi-update-now').click('click', function () {
			return confirm('<?php _e( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', COMPI_TEXT_DOMAIN ); ?>');
		});
	</script>


<?php } elseif ( 'support' === $notice ) {

	$submit_btn_url = esc_url( apply_filters( 'compi_theme_docs_url', Compi()->doc_url . 'theme-compatibility-intergration/', 'theme-compatibility' ) );
	$submit_btn_text = __( 'Theme Integration Guide', COMPI_TEXT_DOMAIN );
	$skip_btn_url = esc_url( add_query_arg( 'hide_compi_theme_support_check', 'true' ) );
	$skip_btn_text = __( 'Hide this notice', COMPI_TEXT_DOMAIN ); ?>

	<div id="message" class="updated compi-message">
		<p><?php sprintf( __( '<strong>Sorry, %s has not met your theme yet so it will not be useful. If you would like your theme and %s to become friends please let us know by opening an issue on Github.', Compi()->name, Compi()->name ), COMPI_TEXT_DOMAIN ); ?></p>

		<p class="submit">
			<a href="<?php echo $submit_btn_url; ?>" class="button-primary"><?php echo $submit_btn_text; ?></a>
			<a class="skip button-primary" href="<?php echo $skip_btn_url; ?>"><?php echo $skip_btn_text; ?></a>
		</p>
	</div>

<?php }
