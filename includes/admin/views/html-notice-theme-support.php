<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated compi-message">
	<p><?php _e( sprintf( '<strong>Sorry, %s has not met your theme yet so it will not be useful. If you would like your theme and %s to become friends please let us know by opening an issue on Github.', Compi()->name, Compi()->name ), COMPI_TEXT_DOMAIN ); ?></p>
	<p class="submit"><a href="<?php echo esc_url( apply_filters( 'compi_theme_docs_url', Compi()->doc_url . 'theme-compatibility-intergration/', 'theme-compatibility' ) ); ?>" class="button-primary"><?php _e( 'Theme Integration Guide', COMPI_TEXT_DOMAIN ); ?></a> <a class="skip button-primary" href="<?php echo esc_url( add_query_arg( 'hide_compi_theme_support_check', 'true' ) ); ?>"><?php _e( 'Hide this notice', COMPI_TEXT_DOMAIN ); ?></a></p>
</div>