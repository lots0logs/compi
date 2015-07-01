<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated compi-message">
	<p><?php echo sprintf( __( '<strong>Welcome to %s</strong> &#8211; You\'re almost ready to start using this plugin :)', COMPI_TEXT_DOMAIN ), Compi()->name ); ?></p>
	<p class="submit"><a href="<?php echo add_query_arg( 'install_compi_pages', 'true', admin_url( 'admin.php?page=' . COMPI_PAGE . '-settings' ) ); ?>" class="button-primary"><?php echo sprintf( __( 'Install %s Pages', COMPI_TEXT_DOMAIN ), Compi()->name ); ?></a> <a class="skip button-primary" href="<?php echo add_query_arg( 'skip_install_compi_pages', 'true', admin_url( 'admin.php?page=' . COMPI_PAGE . '-settings' ) ); ?>"><?php _e( 'Skip setup', COMPI_TEXT_DOMAIN ); ?></a></p>
</div>
