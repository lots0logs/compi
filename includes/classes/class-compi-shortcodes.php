<?php
/**
 * Compi Shortcodes.
 *
 * @since    1.0.0
 * @author wpdots
 * @category Class
 * @package  Compi/Classes
 * @license  GPL-2.0+
 */
class Compi_Shortcodes {

	/**
	 * Initiate Shortcodes
	 *

	 * @since  1.0.0
	 * @access public static
	 */
	public static function init() {
		$shortcodes = array(
			'sample' => __CLASS__ . '::sample',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "compi_{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	} // END init()

	/**
	 * Shortcode Wrapper
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed $function
	 * @param  array $atts (default: array())
	 * @return string
	 */
	public function shortcode_wrapper(
		$function, $atts = array(), $wrapper = array( 'class' => 'compi', 'before' => null, 'after' => null ) ){
		ob_start();

		$before = empty( $wrapper['before'] ) ? '<div class="' . $wrapper['class'] . '">' : $wrapper['before'];
		$after  = empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		echo $before;
		call_user_func( $function, $atts );
		echo $after;

		return ob_get_clean();
	}

	/**
	 * Sample shortcode.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed $atts
	 * @return string
	 */
	public static function sample( $atts ) {
		return $this->shortcode_wrapper( array( 'Compi_Shortcode_Sample', 'output' ), $atts );
	} // END sample()

} // END Compi_Shortcodes class
?>
