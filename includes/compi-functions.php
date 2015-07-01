<?php
/**
 * Plugin Name Page Functions
 *

 * @since    1.0.0
 * @author wpdots
 * @category Core
 * @package  Plugin Name/Functions
 * @license  GPL-2.0+
 */

/**
 * Output generator to aid debugging.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function generator() {
	echo "\n\n" . '<!-- ' . Compi()->name . ' Version -->' . "\n" . '<meta name="generator" content="' . esc_attr( Compi()->name ) .' ' . esc_attr( Compi()->version ) . '" />' . "\n\n";
} // END generator()

?>
