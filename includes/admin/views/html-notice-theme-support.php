<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated compi-message">
	<p><?php _e( sprintf( '<strong>Your theme does not declare %s support</strong> &#8211; if you encounter layout issues please read our integration guide or choose a theme that is compatiable with %s.', Compi()->name, Compi()->name ), COMPI_TEXT_DOMAIN ); ?></p>
	<p class="submit"><a href="<?php echo esc_url( apply_filters( 'compi_theme_docs_url', Compi()->doc_url . 'theme-compatibility-intergration/', 'theme-compatibility' ) ); ?>" class="button-primary"><?php _e( 'Theme Integration Guide', COMPI_TEXT_DOMAIN ); ?></a> <a class="skip button-primary" href="<?php echo esc_url( add_query_arg( 'hide_compi_theme_support_check', 'true' ) ); ?>"><?php _e( 'Hide this notice', COMPI_TEXT_DOMAIN ); ?></a></p>
</div>