<?php
/**
 * Plugin Name Admin Functions
 *
 * @since    1.0.0
 * @author wpdots
 * @category Core
 * @package  Plugin Name
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get all Plugin Name screen ids
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function compi_get_screen_ids() {
	$menu_name = strtolower( str_replace ( ' ', '-', Compi()->menu_name ) );

	$compi_screen_id = COMPI_SCREEN_ID;

	return apply_filters( 'compi_screen_ids', array(
		'plugins',
		'toplevel_page_' . $compi_screen_id,
		'dashboard_page_' . $compi_screen_id . '-about',
		'dashboard_page_' . $compi_screen_id . '-changelog',
		'dashboard_page_' . $compi_screen_id . '-credits',
		'dashboard_page_' . $compi_screen_id . '-translations',
		'dashboard_page_' . $compi_screen_id . '-freedoms',
		$compi_screen_id . '_page_' . $compi_screen_id . '_settings',
		$compi_screen_id . '_page_' . $compi_screen_id . '-settings',
		$compi_screen_id . '_page_' . $compi_screen_id . '-status',
		$menu_name . '_page_' . $compi_screen_id . '_settings',
		$menu_name . '_page_' . $compi_screen_id . '-settings',
		$menu_name . '_page_' . $compi_screen_id . '-status',
	) );
} // END compi_get_screen_ids()

/**
 * Create a page and store the ID in an option.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed $slug Slug for the new page
 * @param  mixed $option Option name to store the page's ID
 * @param  string $page_title (default: '') Title for the new page
 * @param  string $page_content (default: '') Content for the new page
 * @param  int $post_parent (default: 0) Parent for the new page
 * @return int page ID
 */
function compi_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$option_value = get_option( $option );

	if ( $option_value > 0 && get_post( $option_value ) )
		return -1;

	$page_found = null;

	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	}
	else {
		// Search for an existing page with the specified page slug
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $page_found ) {
		if ( ! $option_value ) {
			update_option( $option, $page_found );
		}

		return $page_found;
	}

	$page_data = array(
		'post_status'       => 'publish',
		'post_type'         => 'page',
		'post_author'       => 1,
		'post_name'         => $slug,
		'post_title'        => $page_title,
		'post_content'      => $page_content,
		'post_parent'       => $post_parent,
		'comment_status'    => 'closed'
	);

	$page_id = wp_insert_post( $page_data );

	if ( $option ) {
		update_option( $option, $page_id );
	}

	return $page_id;
} // END compi_create_page()

/**
 * Output admin fields.
 *
 * Loops though the plugin name options array and outputs each field.
 *
 * @since  1.0.0
 * @access public
 * @param  array $options Opens array to output
 */
function compi_admin_fields( $options ) {
	if ( ! class_exists( 'Compi_Admin_Settings' ) ) {
		include 'class-compi-admin-settings.php';
	}

	Compi_Admin_Settings::output_fields( $options );
} // END compi_admin_fields()

/**
 * Update all settings which are passed.
 *
 * @since  1.0.0
 * @access public
 * @param  array $options
 * @return void
 */
function compi_update_options( $options ) {
	if ( ! class_exists( 'Compi_Admin_Settings' ) ) {
		include 'class-compi-admin-settings.php';
	}

	Compi_Admin_Settings::save_fields( $options );
} // END compi_update_options()

/**
 * Get a setting from the settings API.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed $option_name
 * @param  mixed $default
 * @return string
 */
function compi_settings_get_option( $option_name, $default = '' ) {
	if ( ! class_exists( 'Compi_Admin_Settings' ) ) {
		include 'class-compi-admin-settings.php';
	}

	return Compi_Admin_Settings::get_option( $option_name, $default );
} // END compi_settings_get_option()

/**
 * Display Translation progress from Transifex
 *
 * @since  1.0.0
 * @access public
 */
function transifex_display_translation_progress() {
	$stats = new Compi_Transifex_Stats();

	$resource = Compi()->transifex_resources_slug;

	$data_resource = $resource ? " data-resource-slug='{$resource}'" : '';
	?>
	<div class='transifex-stats' data-project-slug='<?php echo Compi()->transifex_project_slug; ?>'<?php echo $data_resource; ?>/>
		<?php $stats->display_translations_progress(); ?>
	</div>
	<?php
} // END transifex_display_translation_progress()

/**
 * Display Translation Stats from Transifex
 *
 * @since  1.0.0
 * @access public
 */
function transifex_display_translators() {
	$stats = new Compi_Transifex_Stats();
	?>
	<div class='transifex-stats-contributors' data-project-slug='<?php echo Compi()->transifex_project_slug; ?>'/>
		<?php $stats->display_contributors(); ?>
	</div>
	<?php
} // END transifex_display_translators()

/**
 * Hooks Plugin Name actions, when present in the $_REQUEST superglobal.
 * Every compi_action present in $_REQUEST is called using
 * WordPress's do_action function. These functions are called on admin_init.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function compi_do_actions() {
	if ( isset( $_REQUEST['compi_action'] ) ) {
		do_action( 'compi_' . $_REQUEST['compi_action'], $_REQUEST );
	}
} // END compi_do_actions()
add_action( 'admin_init', 'compi_do_actions' );

?>
