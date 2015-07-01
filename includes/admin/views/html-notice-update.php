<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated compi-message">
	<p><?php echo sprintf( __( '<strong>%s Data Update Required</strong> &#8211; We just need to update your install to the latest version', COMPI_TEXT_DOMAIN ), Compi()->name ); ?></p>
	<p class="submit"><a href="<?php echo add_query_arg( 'do_update_compi', 'true', admin_url( 'admin.php?page=' . COMPI_PAGE . '-settings' ) ); ?>" class="compi-update-now button-primary"><?php _e( 'Run the updater', COMPI_TEXT_DOMAIN ); ?></a></p>
</div>
<script type="text/javascript">
	jQuery('.compi-update-now').click('click', function(){
		return confirm('<?php _e( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', COMPI_TEXT_DOMAIN ); ?>');
	});
</script>
