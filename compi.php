<?php
/*
 * Plugin Name: Compi
 * Plugin URI: http://wpdots.com/plugins/compi
 * Description: The perfect companion for Divi.
 * Version: 1.0.0
 * Author: wpdots
 * Author URI: http://wpdots.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: compi
 * Domain Path:       languages
 * Network: true
 * GitHub Plugin URI: 
 *
 * Compi is distributed under the terms of the
 * GNU General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * Compi is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Compi. If not, see <http://www.gnu.org/licenses/>.
 *

 * @package Compi
 * @author wpdots
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Compi' ) ) {

/**
 * Main Plugin Name Class
 *

 * @since 1.0.0
 */
final class Compi {

	/**
	 * The single instance of the class
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $_instance = null;

	/**
	 * Slug
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $plugin_slug = 'compi';

	/**
	 * Text Domain
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $text_domain = 'compi';

	/**
	 * The Plugin Name.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $name = "Compi";

	/**
	 * The Plugin Version.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $version = "1.0.2";

	/**
	 * The WordPress version the plugin requires minumum.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $wp_version_min = "4.0";

	/**
	 * Memory Limit required for the Plugin.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $memory_limit = '320'; // returns 32 MB

	/**
	 * The Plugin URI.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $web_url = "http://www.sebastiendumont.com/plugins/boilerplates/wordpress-plugin-boilerplate/";

	/**
	 * The Plugin documentation URI.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $doc_url = "https://github.com/seb86/WordPress-Plugin-Boilerplate/wiki/";

	/**
	 * The WordPress.org Plugin URI.
	 *

	 *          plugin slug given for your wordpress repository
	 * @since   1.0.0
	 * @example https://wordpress.org/plugins/your-compi
	 * @access  public
	 * @var     string
	 */
	public $wp_plugin_url = "https://wordpress.org/plugins/your-compi";

	/**
	 * The WordPress.org Plugin Support URI.
	 *

	 *          plugin slug given for your wordpress repository
	 * @since   1.0.0
	 * @example https://wordpress.org/support/plugin/your-compi
	 * @access  public
	 * @var     string
	 */
	public $wp_plugin_support_url = "https://wordpress.org/support/plugin/your-compi";

	/**
	 * The WordPress.org Plugin Review URI.
	 *

	 *          plugin slug given for your wordpress repository
	 * @since   1.0.2
	 * @example https://wordpress.org/support/view/plugin-reviews/your-compi
	 * @access  public
	 * @var     string
	 */
	public $wp_plugin_review_url = 'https://wordpress.org/support/view/plugin-reviews/your-compi?filter=5#postform';

	/**
	 * GitHub Repo URI
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $github_repo_url = "https://github.com/seb86/WordPress-Plugin-Boilerplate/";

	/**
	 * Transifex Project URI
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $transifex_project_url = "https://www.transifex.com/projects/p/wordpress-plugin-boilerplate/";

	/**
	 * Transifex Project Slug
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $transifex_project_slug = 'compi';

	/**
	 * Transifex Resources Slug
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $transifex_resources_slug = 'compi';

	/**
	 * The Plugin menu name.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $menu_name = "My Plugin";

	/**
	 * The Plugin title page name.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $title_name = "Compi";

	/**
	 * Manage Plugin.
	 *

	 *         the user must have to control the plugin.
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $manage_plugin = "manage_compi";

	/**
	 * Display single submenu link to the settings page
	 * or provide a submenu link for each settings tab.
	 *
	 * If value is empty or 'no' then just a single submenu
	 * will be available. If value is 'yes' then each settings
	 * tab will have it's own submenu link for quicker access.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $full_settings_menu = "no";

	/**
	 * Facebook Page Name or User ID.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $facebook_page = "SebD86";

	/**
	 * Twitter Username.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $twitter_username = "sebd86";

	/**
	 * Google Plus ID Number.
	 *

	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $google_plus_id = "114016411970997366558";

	/**
	 * Main Plugin Name Instance
	 *
	 * Ensures only one instance of Plugin Name is loaded or can be loaded.
	 *

	 * @since  1.0.0
	 * @access public static
	 * @see    Compi()
	 * @return Plugin Name instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new Compi;
		}
		return self::$_instance;
	} // END instance()

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'compi' ), $this->version );
	} // END __clone()

	/**
	 * Disable unserializing of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'compi' ), $this->version );
	} // END __wakeup()

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Auto-load classes on demand
		if ( function_exists( "__autoload" ) )
			spl_autoload_register( "__autoload" );

		spl_autoload_register( array( $this, 'autoload' ) );

		// Define constants
		$this->define_constants();

		// Check plugin requirements
		$this->check_requirements();

		// Include required files
		$this->includes();

		// Hooks
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		add_filter( 'plugin_row_meta',                                    array( $this, 'plugin_row_meta' ), 10, 2 );
		add_action( 'widgets_init',                                       array( $this, 'include_widgets' ) );
		add_action( 'init',                                               array( $this, 'init_compi' ), 0 );
		add_action( 'init',                                               array( 'Compi_Shortcodes', 'init' ) );

		// Loaded action
		do_action( 'compi_loaded' );
	} // END __construct()

	/**
	 * Plugin action links.
	 *

	 * @since  1.0.0
	 * @access public
	 * @param  mixed $links
	 * @return void
	 */
	public function action_links( $links ) {
		if( current_user_can( $this->manage_plugin ) ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=' . COMPI_PAGE . '-settings' ) . '">' . __( 'Settings', 'compi' ) . '</a>',
				'<a href="' . $this->wp_plugin_support_url . '" target="_blank">' . __( 'Support', 'compi' ) . '</a>'
			);

			return array_merge( $plugin_links, $links );
		}

		return $links;
	} // END action_links()

	/**
	 * Plugin row meta links
	 *
	 * @filter compi_about_text_link
	 * @filter compi_documentation_url
	 * @since  1.0.0
	 * @access public
	 * @param  array  $input already defined meta links
	 * @param  string $file  plugin file path and name being processed
	 * @return array  $input
	 */
	public function plugin_row_meta( $input, $file ) {
		if ( plugin_basename( __FILE__ ) !== $file ) {
			return $input;
		}

		$links = array(
			'<a href="' . admin_url( 'index.php?page=' . COMPI_PAGE . '-about' ) . '">' . esc_html( apply_filters( 'compi_about_text_link', __( 'Getting Started', 'compi' ) ) ) . '</a>',
			'<a href="' . admin_url( 'index.php?page=' . COMPI_PAGE . '-credits' ) . '">' . esc_html( __( 'Credits', 'compi' ) ) . '</a>',
			'<a href="' . esc_url( apply_filters( 'compi_documentation_url', $this->doc_url ) ) . '">' . __( 'Documentation', 'compi' ) . '</a>',
		);

		$input = array_merge( $input, $links );

		return $input;
	} // END plugin_row_meta()

	/**
	 * Auto-load Plugin Name classes on demand to reduce memory consumption.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed $class
	 * @return void
	 */
	public function autoload( $class ) {
		$path  = null;
		$class = strtolower( $class );
		$file  = 'class-' . str_replace( '_', '-', $class ) . '.php';

		$class = strtolower( $class );

		if ( strpos( $class, 'compi_shortcode_' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/classes/shortcodes/';
		}
		else if ( strpos( $class, 'compi_' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/classes/';
		}
		else if ( strpos( $class, 'compi_admin' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/admin/';
		}

		if ( $path && is_readable( $path . $file ) ) {
			include_once( $path . $file );
			return;
		}

		// Fallback
		if ( strpos( $class, 'compi_' ) === 0 ) {
			$path = $this->plugin_path() . '/includes/';
		}

		if ( $path && is_readable( $path . $file ) ) {
			include_once( $path . $file );
			return;
		}

	} // END autoload()

	/**
	 * Define Constants
	 *

	 *         2. Change 'compi' with the plugin slug of your plugin on "WordPress.org"
	 * @since  1.0.0
	 * @access private
	 */
	private function define_constants() {
		if ( ! defined( 'COMPI' ) )                       define( 'COMPI', $this->name );
		if ( ! defined( 'COMPI_FILE' ) )                  define( 'COMPI_FILE', __FILE__ );
		if ( ! defined( 'COMPI_VERSION' ) )               define( 'COMPI_VERSION', $this->version );
		if ( ! defined( 'COMPI_WP_VERSION_REQUIRE' ) )    define( 'COMPI_WP_VERSION_REQUIRE', $this->wp_version_min );
		if ( ! defined( 'COMPI_MENU_NAME' ) )             define( 'COMPI_MENU_NAME', strtolower( str_replace( ' ', '-', $this->menu_name ) ) );
		if ( ! defined( 'COMPI_PAGE' ) )                  define( 'COMPI_PAGE', str_replace('_', '-', $this->plugin_slug) );
		if ( ! defined( 'COMPI_SCREEN_ID' ) )             define( 'COMPI_SCREEN_ID', strtolower( str_replace( ' ', '-', COMPI_PAGE ) ) );
		if ( ! defined( 'COMPI_SLUG' ) )                  define( 'COMPI_SLUG', $this->plugin_slug );
		if ( ! defined( 'COMPI_TEXT_DOMAIN' ) )           define( 'COMPI_TEXT_DOMAIN', $this->text_domain );
		if ( ! defined( 'COMPI_DEFAULT_SETTINGS_TAB' ) )  define( 'COMPI_DEFAULT_SETTINGS_TAB', 'tab_one');

		if ( ! defined( 'COMPI_README_FILE' ) )           define( 'COMPI_README_FILE', 'http://plugins.svn.wordpress.org/compi/trunk/readme.txt' );

		if ( ! defined( 'COMPI_GITHUB_REPO_URI' ) )       define( 'COMPI_GITHUB_REPO_URI', $this->github_repo_url );
		if ( ! defined( 'COMPI_TRANSIFEX_PROJECT_URI' ) ) define( 'COMPI_TRANSIFEX_PROJECT_URI', $this->transifex_project_url );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		define( 'COMPI_SCRIPT_MODE', $suffix );
	} // END define_constants()

	/**
	 * Checks that the WordPress setup meets the plugin requirements.
	 *
	 * @since  1.0.0
	 * @access private
	 * @global string $wp_version
	 * @return bool
	 */
	private function check_requirements() {
		global $wp_version;

		if ( ! version_compare( $wp_version, COMPI_WP_VERSION_REQUIRE, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'display_req_notice' ) );
			return false;
		}

		return true;
	} // END check_requirements()

	/**
	 * Display the requirement notice.
	 *
	 * @since 1.0.0
	 * @access static
	 */
	static function display_req_notice() {
		echo '<div id="message" class="error"><p><strong>';
		echo sprintf( __('Sorry, %s requires WordPress ' . COMPI_WP_VERSION_REQUIRE . ' or higher. Please upgrade your WordPress setup', 'compi'), COMPI );
		echo '</strong></p></div>';
	} // END display_req_notice()

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function includes() {
		include_once( 'includes/compi-core-functions.php' ); // Contains core functions for the front/back end.

		if ( is_admin() ) {
			$this->admin_includes();
		}

		if ( defined('DOING_AJAX') ) {
			$this->ajax_includes();
		}

		if ( ! is_admin() || defined('DOING_AJAX') ) {
			$this->frontend_includes();
		}

		include_once( 'includes/compi-hooks.php' ); // Hooks used in either the front or the admin
	} // END includes()

	/**
	 * Include required admin files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_includes() {
		include_once( 'includes/admin/class-compi-install.php' ); // Install plugin
		include_once( 'includes/admin/class-compi-admin.php' ); // Admin section
	} // END admin_includes()

	/**
	 * Include required ajax files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function ajax_includes() {
		include_once( 'includes/compi-ajax.php' ); // Ajax functions for admin and the front-end
	} // END ajax_includes()

	/**
	 * Include required frontend files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_includes() {
		// Functions
		include_once( 'includes/compi-template-hooks.php' ); // Include template hooks for themes to remove/modify them
		include_once( 'includes/compi-functions.php' ); // Contains functions for various front-end events

		// Classes
		include_once( 'includes/classes/class-compi-shortcodes.php' ); // Shortcodes class
	} // END frontend_includes()

	/**
	 * Include widgets.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function include_widgets() {
		include_once( 'includes/widgets.php' ); // Includes the widgets listed and registers each one.
	} // END include_widgets()

	/**
	 * Runs when the plugin is initialized.
	 *
	 * @since  1.0.0
	 * @access public
	 * @filter compi_force_ssl_filter
	 */
	public function init_compi() {
		// Before init action
		do_action( 'before_compi_init' );

		// Set up localisation
		$this->load_plugin_textdomain();

		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// This will run on the frontend and for ajax requests
		if ( ! is_admin() || defined('DOING_AJAX') ) {
			$this->shortcodes = new Compi_Shortcodes(); // Shortcodes class, controls all frontend shortcodes

			/**
			 * If we're on the frontend, ensure any links output to a page
			 * (when viewing via HTTPS) are also served over HTTPS.
			 */
			$ssl_filters = apply_filters( 'compi_force_ssl_filter', array( 'post_thumbnail_html', 'widget_text', 'wp_get_attachment_url', 'wp_get_attachment_image_attributes', 'wp_get_attachment_url', 'option_stylesheet_url', 'option_template_url', 'script_loader_src', 'style_loader_src', 'template_directory_uri', 'stylesheet_directory_uri', 'site_url' ) );

			foreach ( $ssl_filters as $filter ) {
				add_filter( $filter, array( $this, 'force_ssl' ) );
			}
		}

		// Init action
		do_action( 'compi_init' );
	} // END init_compi()

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any
	 * following ones if the same translation is present.
	 *
	 * @since  1.0.0
	 * @access public
	 * @filter compi_languages_directory
	 * @filter plugin_locale
	 * @return void
	 */
	public function load_plugin_textdomain() {
		// Set filter for plugin's languages directory
		$lang_dir = dirname( plugin_basename( COMPI_FILE ) ) . '/languages/';
		$lang_dir = apply_filters( 'compi_languages_directory', $lang_dir );

		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale',  get_locale(), $this->text_domain );
		$mofile = sprintf( '%1$s-%2$s.mo', $this->text_domain, $locale );

		// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/' . $this->text_domain . '/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/compi/ folder
			load_textdomain( $this->text_domain, $mofile_global );
		}
		else if ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/compi/languages/ folder
			load_textdomain( $this->text_domain, $mofile_local );
		}
		else {
			// Load the default language files
			load_plugin_textdomain( $this->text_domain, false, $lang_dir );
		}
	} // END load_plugin_textdomain()

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	} // END plugin_url()

	/**
	 * Get the plugin path.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	} // END plugin_path()

	/**
	 * Get the plugin template path.
	 *
	 * @since  1.0.0
	 * @access public
	 * @filter compi_template_path
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'compi_template_path', 'compi/' );
	} // END template_path()

	/**
	 * Force SSL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed $content
	 * @return void
	 */
	public function force_ssl( $content ) {
		if ( is_ssl() ) {
			if ( is_array( $content ) ) {
				$content = array_map( array( $this, 'force_ssl' ) , $content );
			}
			else {
				$content = str_replace( 'http:', 'https:', $content );
			}
		}
		return $content;
	} // END force_ssl()

	/**
	 * Registers and enqueues stylesheets and javascripts
	 * for the administration panel and the front of the site.
	 *
	 * @since  1.0.0
	 * @access private
	 * @filter compi_admin_params
	 * @filter compi_params
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {
			// Main Plugin Javascript
			$this->load_file( $this->plugin_slug . '_admin_script', '/assets/js/admin/compi' . COMPI_SCRIPT_MODE . '.js', true, array('jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip'), $this->version );
			// Plugin Menu
			$this->load_file( $this->plugin_slug . '_admin_menu_script', '/assets/js/admin/admin-menu.' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );

			// Block UI
			$this->load_file( 'jquery-blockui', '/assets/js/jquery-blockui/jquery.blockUI' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), '2.60' );

			// TipTip
			$this->load_file( 'jquery-tiptip', '/assets/js/jquery-tiptip/jquery.tipTip' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );

			// Chosen
			$this->load_file( 'ajax-chosen', '/assets/js/chosen/ajax-chosen.jquery' . COMPI_SCRIPT_MODE . '.js', true, array('jquery', 'chosen'), $this->version );
			$this->load_file( 'chosen', '/assets/js/chosen/chosen.jquery' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );

			// Chosen RTL
			if ( is_rtl() ) {
				$this->load_file( 'chosen-rtl', '/assets/js/chosen/chosen-rtl' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );
			}

			// prettyPhoto
			$this->load_file( 'jquery-prettyphoto', '/assets/js/prettyPhoto/jquery.prettyPhoto' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );
			$this->load_file( 'jquery-prettyphoto-init', '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );
			$this->load_file( 'prettyPhoto-style', '/assets/css/prettyPhoto.css' );

			// Transifex
			$this->load_file( 'transifex', '/assets/js/admin/transifex' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );

			// Variables for Admin JavaScripts
			wp_localize_script( $this->plugin_slug . '_admin_script', 'compi_admin_params', apply_filters( 'compi_admin_params', array(
				'ajaxurl'            => admin_url('admin-ajax.php'),
				'no_result'          => __( 'No results', 'compi' ),
				'plugin_url'         => $this->plugin_url(),
				'i18n_nav_warning'   => __( 'The changes you made will be lost if you navigate away from this page.', 'compi' ),
				'full_settings_menu' => $this->full_settings_menu,
				'plugin_menu_name'   => $this->menu_name,
				'plugin_screen_id'   => COMPI_SCREEN_ID,
				'_tab_one'           => __( 'First Tab', 'compi' ),
				'_tab_two'           => __( 'Second Tab', 'compi' ),
				'system_status'      => __( 'System Status', 'compi' ),
				'tools'              => __( 'Tools', 'compi' ),
				'_import'            => __( 'Import', 'compi' ),
				'_export'            => __( 'Export', 'compi' ),
			) ) );

			// Stylesheets
			$this->load_file( $this->plugin_slug . '_admin_style', '/assets/css/admin/compi.css' );
			$this->load_file( $this->plugin_slug . '_admin_menu_styles', '/assets/css/admin/menu.css' );
		}
		else {
			$this->load_file( $this->plugin_slug . '-script', '/assets/js/frontend/compi' . COMPI_SCRIPT_MODE . '.js', true );

			// prettyPhoto
			$this->load_file( 'jquery-prettyphoto', '/assets/js/prettyPhoto/jquery.prettyPhoto' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );
			$this->load_file( 'jquery-prettyphoto-init', '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . COMPI_SCRIPT_MODE . '.js', true, array('jquery'), $this->version );
			$this->load_file( 'prettyPhoto-style', '/assets/css/prettyPhoto.css' );

			// Plugin Name Stylesheet
			$this->load_file( $this->plugin_slug . '-style', '/assets/css/compi.css' );

			// Variables for JS scripts
			wp_localize_script( $this->plugin_slug . '-script', 'compi_params', apply_filters( 'compi_params', array(
				'plugin_url' => $this->plugin_url(),
			) ) );
		} // end if/else
	} // END register_scripts_and_styles()

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  string  $name	    The ID to register with WordPress.
	 * @param  string  $file_path	The path to the actual file.
	 * @param  bool    $is_script Optional, argument for if the incoming file_path is a JavaScript source file.
	 * @param  array   $support   Optional, for requiring other javascripts for the source file you are calling.
	 * @param  string  $version   Optional, can match the version of the plugin or version of the source file.
	 * @global string  $wp_version
	 */
	private function load_file( $name, $file_path, $is_script = false, $support = array(), $version = '' ) {
		global $wp_version;

		$url  = $this->plugin_url() . $file_path;
		$file = $this->plugin_path() . $file_path;

		if ( file_exists( $file ) ) {
			if ( $is_script ) {
				wp_register_script( $name, $url, $support, $version );
				wp_enqueue_script( $name );
			}
			else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

		wp_enqueue_style( 'wp-color-picker' );
		if ( is_admin() && $wp_version >= '3.8' ) {
			wp_enqueue_style( 'dashicons' ); // Loads only in WordPress 3.8 and up.
		}

	} // END load_file()

} // END Compi()

} // END class_exists('Compi')

/**
 * Returns the instance of Compi to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Plugin Name
 */
function Compi() {
	return Compi::instance();
}

// Global for backwards compatibility.
$GLOBALS["compi"] = Compi();

?>
