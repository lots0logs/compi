<?php
/**
 * Setup menus in the WordPress admin.
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

if ( ! class_exists( 'Compi_Admin_Menus' ) ) {

	/**
	 * Class - Compi_Admin_Menus
	 *
	 * @since 1.0.0
	 */
	class Compi_Admin_Menus {

		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {

			// Add admin menus
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
			// Add menu seperator
			add_action( 'admin_init', array( $this, 'add_admin_menu_separator' ) );
			// Add menu order and highlighter
			add_action( 'admin_head', array( $this, 'menu_highlight' ) );
			add_filter( 'menu_order', array( $this, 'menu_order' ) );
			//add_filter( 'parent_file',     array( $this, 'menu_parent_file' ) );
			add_filter( 'custom_menu_order', array( $this, 'custom_menu_order' ) );
		} // END __construct()

		/**
		 * Add menu seperator
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param  $position
		 *
		 * @global $menu
		 */
		public function add_admin_menu_separator( $position ) {

			global $menu;

			if ( current_user_can( Compi()->manage_plugin ) ) {
				$menu[ $position ] = array(
					0 => '',
					1 => 'read',
					2 => 'separator' . $position,
					3 => '',
					4 => 'wp-menu-separator compi',
				);
			}
		} // END add_admin_menu_separator()

		/**
		 * Add menu items.
		 *
		 * @since  1.0.0
		 * @access public
		 * @global $menu
		 * @global $compi
		 * @global $wp_version
		 */
		public function admin_menu() {

			global $menu, $compi, $wp_version;

			if ( current_user_can( Compi()->manage_plugin ) ) {
				$menu[] = array( '', 'read', 'separator-compi', '', 'wp-menu-separator compi' );
			}

			add_menu_page( Compi()->title_name, Compi()->menu_name, Compi()->manage_plugin, COMPI_PAGE, array(
				$this,
				'compi_page',
			), null );

			$settings_menu = isset( Compi()->full_settings_menu ) ? Compi()->full_settings_menu : '';

			if ( $settings_menu == '' || $settings_menu == 'no' ) {
				$settings_page = add_submenu_page( COMPI_PAGE, sprintf( __( '%s Settings', COMPI_TEXT_DOMAIN ), Compi()->title_name ), __( 'Settings', COMPI_TEXT_DOMAIN ), Compi()->manage_plugin, COMPI_PAGE . '-settings', array(
					$this,
					'settings_page',
				) );
			} else {
				// Load the main settings page.
				$settings_page = add_submenu_page( COMPI_PAGE, sprintf( __( '%s Settings', COMPI_TEXT_DOMAIN ), Compi()->title_name ), __( 'Settings', COMPI_TEXT_DOMAIN ), Compi()->manage_plugin, COMPI_PAGE . '-settings', array(
					$this,
					'settings_page',
				) );

				// List the menu name and slug for each tab to have it's own settings shortcut.
				$settings_submenus = apply_filters( 'compi_settings_submenu_array', array(
					array(
						'menu_name' => __( 'First Tab', COMPI_TEXT_DOMAIN ),
						'menu_slug' => 'tab_one',
					),
					array(
						'menu_name' => __( 'Second Tab', COMPI_TEXT_DOMAIN ),
						'menu_slug' => 'tab_two',
					),
				) );

				// Each settings tab will create a submenu under Compi menu.
				foreach ( $settings_submenus as $tab ) {
					$settings_page .= add_submenu_page( COMPI_PAGE, sprintf( __( '%s Settings', COMPI_TEXT_DOMAIN ), Compi()->title_name ), $tab['menu_name'], Compi()->manage_plugin, COMPI_PAGE . '-settings&tab=' . $tab['menu_slug'], array(
						$this,
						'settings_page',
					) );
				}
			}

			add_submenu_page( COMPI_PAGE, sprintf( __( '%s Status', COMPI_TEXT_DOMAIN ), Compi()->title_name ), __( 'System Status', COMPI_TEXT_DOMAIN ), Compi()->manage_plugin, COMPI_PAGE . '-status', array(
				$this,
				'status_page',
			) );

			add_submenu_page( COMPI_PAGE, sprintf( __( '%s Tools', COMPI_TEXT_DOMAIN ), Compi()->title_name ), __( 'Tools', COMPI_TEXT_DOMAIN ), Compi()->manage_plugin, COMPI_PAGE . '-status&tab=tools', array(
				$this,
				'status_page',
			) );

			//add_submenu_page( COMPI_PAGE, sprintf( __( '%s Import', COMPI_TEXT_DOMAIN ), Compi()->title_name ), __( 'Import', COMPI_TEXT_DOMAIN ), Compi()->manage_plugin, COMPI_PAGE . '-status&tab=import', array( &$this, 'port_page' ) );

			//add_submenu_page( COMPI_PAGE, sprintf( __( '%s Export', COMPI_TEXT_DOMAIN ), Compi()->title_name ), __( 'Export', COMPI_TEXT_DOMAIN ), Compi()->manage_plugin, COMPI_PAGE . '-status&tab=export', array( &$this, 'port_page' ) );

			register_setting( 'compi_status_settings_fields', 'compi_status_options' );
		} // END admin_menu()

		/**
		 * Highlights the correct top level admin menu item.
		 *
		 * @since  1.0.0
		 * @access public
		 * @global $submenu
		 * @global $parent_file
		 * @global $submenu_file
		 * @global $self
		 * @return void
		 */
		public function menu_highlight() {

			global $submenu, $parent_file, $submenu_file, $self;

			$to_highlight_types = array( 'tools', 'import', 'export' );

			if ( isset( $_GET['tab'] ) ) {
				if ( in_array( $_GET['tab'], $to_highlight_types ) ) {
					$submenu_file = 'admin.php?page=' . COMPI_PAGE . '-settings&tab=' . esc_attr( $_GET['tab'] );
					$parent_file  = COMPI_PAGE;
				}
			}

			/*if ( isset( $submenu['compi'] ) && isset( $submenu['compi'][1] ) ) {
				$submenu['compi'][0] = $submenu['compi'][1];
				unset( $submenu['compi'][1] );
			}*/
		} // END menu_highlight()

		/**
		 * Reorder Compi menu items in admin.
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param  mixed $menu_order
		 *
		 * @return array
		 */
		public function menu_order( $menu_order ) {

			// Initialize our custom order array
			$compi_menu_order = array();

			// Get the index of our custom separator
			$compi_separator = array_search( 'separator-compi', $menu_order );

			// Loop through menu order and do some rearranging
			foreach ( $menu_order as $index => $item ) {

				if ( ( ( str_replace( '_', '-', COMPI_SLUG ) ) == $item ) ) {
					$compi_menu_order[] = 'separator-' . str_replace( '_', '-', COMPI_SLUG );
					$compi_menu_order[] = $item;
					$compi_menu_order[] = 'admin.php?page=' . COMPI_PAGE;
					unset( $menu_order[ $compi_separator ] );
				} elseif ( ! in_array( $item, array( 'separator-' . str_replace( '_', '-', COMPI_SLUG ) ) ) ) {
					$compi_menu_order[] = $item;
				}

			}

			// Return menu order
			return $compi_menu_order;
		} // END menu_order()

		/**
		 *
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param  $parent_file
		 *
		 * @global $current_screen
		 * @global $pagenow
		 * @global $submenu_file
		 * @return string
		 */
		public function menu_parent_file( $parent_file ) {

			global $current_screen, $pagenow, $submenu_file;

			switch ( $pagenow ) {
				case 'admin.php':
					if ( isset( $_GET['tab'] ) ) {
						if ( $_GET['tab'] == 'tools' ) {
							$parent_file = 'admin.php?page=' . COMPI_SLUG . '&tab=tools';
						}
					}
					break;
				default:
					$parent_file = $parent_file;
					break;
			}

			return $parent_file;
		} // END menu_parent_file()

		/**
		 * Sets the menu order depending on user access.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return bool
		 */
		public function custom_menu_order() {

			if ( ! current_user_can( Compi()->manage_plugin ) ) {
				return false;
			}

			return true;
		} // END custom_menu_order()

		/**
		 * Initialize the Compi main page.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function compi_page() {

			include_once( 'class-compi-admin-page.php' );
			Compi_Admin_Page::output();
		} // END compi_page()

		/**
		 * Initialize the Compi settings page.
		 * @since  1.0.0
		 * @access public
		 */
		public function settings_page() {

			include_once( 'class-compi-admin-settings.php' );
			Compi_Admin_Settings::output();
		}

		/**
		 * Initialize the Compi status page.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function status_page() {

			include_once( 'class-compi-admin-status.php' );
			Compi_Admin_Status::output();
		} // END status_page()

		/**
		 * Initialize the Compi import and export page.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function port_page() {

			include_once( 'class-compi-admin-import-export.php' );
			Compi_Admin_Import_Export::output();
		} // END port_page()

	} // END Compi_Admin_Menus class.

} // END if class exists.

return new Compi_Admin_Menus();

?>
