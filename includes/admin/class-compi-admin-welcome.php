<?php
/**
 * Welcome Page Class
 *
 * Shows a feature overview of your plugin, new changes, credits and translations.
 *
 * @since    1.0.0
 * @author wpdots
 * @category Admin
 * @package  Compi
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Compi_Admin_Welcome' ) ) {

/**
 * Compi_Admin_Welcome class.
 */
class Compi_Admin_Welcome {

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
*/
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome' ) );
	} // END __construct()

	/**
	 * Register your dashboard pages, page slug, title, capability and screen.
	 *

	 * @since  1.0.0
	 * @filter compi_register_dashboard_pages
	 * @access public
	 * @return array()
	 */
	public function register_admin_menu() {
		return $menus = apply_filters( 'compi_register_dashboard_pages', array(
					array(
						'id'         => COMPI_PAGE . '-about',
						'title'      => sprintf( __( 'Welcome to %s', COMPI_TEXT_DOMAIN ), Compi()->name ),
						'capability' => 'manage_options',
						'screen'     => 'about_screen',
						'tab_name'   => __( 'Getting Started', COMPI_TEXT_DOMAIN )
					),
					array(
						'id'         => COMPI_PAGE . '-changelog',
						'title'      => sprintf( __( '%s Changelog', COMPI_TEXT_DOMAIN ), Compi()->name ),
						'capability' => 'manage_options',
						'screen'     => 'changelog_screen',
						'tab_name'   => __( 'Changelog', COMPI_TEXT_DOMAIN )
					),
					array(
						'id'         => COMPI_PAGE . '-credits',
						'title'      => sprintf( __( '%s Credits', COMPI_TEXT_DOMAIN ), Compi()->name ),
						'capability' => 'manage_options',
						'screen'     => 'credits_screen',
						'tab_name'   => __( 'Credits', COMPI_TEXT_DOMAIN )
					),
					array(
						'id'         => COMPI_PAGE . '-translations',
						'title'      => sprintf( __( '%s Translations', COMPI_TEXT_DOMAIN ), Compi()->name ),
						'capability' => 'manage_options',
						'screen'     => 'translations_screen',
						'tab_name'   => __( 'Translations', COMPI_TEXT_DOMAIN )
					),
					array(
						'id'         => COMPI_PAGE . '-freedoms',
						'title'      => sprintf( __( '%s Freedoms', COMPI_TEXT_DOMAIN ), Compi()->name ),
						'capability' => 'manage_options',
						'screen'     => 'freedoms_screen',
						'tab_name'   => __( 'Freedoms', COMPI_TEXT_DOMAIN )
					),
		) );
	} // END register_admin_menu()

	/**
	 * Register the Dashboard Pages which are normally hidden.
	 * These pages are used to render the Welcome and Credits pages.
	 * Can be accessed again via the version number link at the
	 * bottom of Compi pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menus() {
		foreach ( $this->register_admin_menu() as $menu ) {
			$page_title = $menu['title'];
			$dashboard_page = add_dashboard_page( $page_title, $page_title, $menu['capability'], $menu['id'], array( $this, $menu['screen'] ) );
			add_action( 'admin_print_styles-'. $dashboard_page, array( $this, 'admin_css' ) );
		}
	} // END admin_menus()

	/**
	 * Remove submenus. This hides each submenu under the dashboard page.
	 *

	 * @since  1.0.0
	 * @filter compi_remove_submenus
	 * @access public
	 * @return array()
	 */
	public function remove_submenus() {
		$submenus = apply_filters( 'compi_remove_submenus', array(
			array( 'id' => 'about' ),
			array( 'id' => 'changelog' ),
			array( 'id' => 'credits' ),
			array( 'id' => 'translations' ),
			array( 'id' => 'freedoms' ),
		) );

		return $submenus;
	} // END remove_submenus()

	/**
	 * Loads the stylesheets for each of the dashboard pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_css() {
		wp_enqueue_style( 'compi-activation', Compi()->plugin_url() . '/assets/css/admin/welcome.css' );
	} // END admin_css()

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_head() {
		// This removes each submenu listed in function 'remove_submenus'.
		foreach ( $this->remove_submenus() as $submenu ) {
			remove_submenu_page( 'index.php', COMPI_PAGE . '-' . $submenu['id'] );
		}

		// Badge for welcome page
		$badge_url = Compi()->plugin_url() . '/assets/images/welcome/compi-badge.png';
		?>
		<style type="text/css">
		.compi-badge {
			background-image: url('<?php echo $badge_url; ?>') !important;
		}

		@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
			.compi-badge {
				background-image: url('<?php echo str_replace( 'badge.png', 'badge@2x.png', $badge_url ); ?>') !important;
			}
		}
		</style>
		<?php
	} // END admin_head()

	/**
	 * Intro text and links shown on all welcome pages.
	 *
	 * @since  1.0.0
	 * @access private
	 * @filter compi_docs_url
	 * @filter compi_welcome_twitter_username
	 * @return void
	 */
	private function intro() {
		// Flush after upgrades
		if ( ! empty( $_GET['compi-updated'] ) || ! empty( $_GET['compi-installed'] ) ) {
			flush_rewrite_rules();
		}

		// Drop minor version if 0
		$major_version = substr( Compi()->version, 0, 3 );
		?>
		<h1><?php _e( sprintf( 'Welcome to %s %s', Compi()->name, $major_version ), COMPI_TEXT_DOMAIN ); ?></h1>

		<div class="about-text compi-about-text">
			<?php
				do_action( 'compi_welcome_text_before' );

				if ( ! empty( $_GET['compi-installed'] ) ) {
					$message = __( 'Thanks, all done!', COMPI_TEXT_DOMAIN );
				}
				elseif ( ! empty( $_GET['compi-updated'] ) ) {
					$message = __( 'Thank you for updating to the latest version!', COMPI_TEXT_DOMAIN );
				}
				else {
					$message = __( 'Thanks for installing!', COMPI_TEXT_DOMAIN );
				}

				echo sprintf( __( '%s %s %s is a powerful, stable, and secure plugin boilerplate. I hope you enjoy it.', COMPI_TEXT_DOMAIN ), $message, Compi()->name, $major_version );

				do_action( 'compi_welcome_text_after' );
			?>
		</div>

		<div class="compi-badge"><?php printf( __( 'Version %s', COMPI_TEXT_DOMAIN ), Compi()->version ); ?></div>

		<div class="compi-social-links">
			<a class="facebook_link" href="https://www.facebook.com/<?php echo Compi()->facebook_page; ?>" target="_blank">
				<span class="dashicons dashicons-facebook-alt"></span>
			</a>

			<a class="twitter_link" href="https://twitter.com/<?php echo Compi()->twitter_username; ?>" target="_blank">
				<span class="dashicons dashicons-twitter"></span>
			</a>

			<a class="googleplus_link" href="https://plus.google.com/<?php echo Compi()->google_plus_id; ?>" target="_blank">
				<span class="dashicons dashicons-googleplus"></span>
			</a>

		</div><!-- .compi-social-links -->

		<p class="compi-actions">
			<a href="<?php echo admin_url( 'admin.php?page=' . COMPI_PAGE . '-settings' ); ?>" class="button button-primary"><?php _e( 'Settings', COMPI_TEXT_DOMAIN ); ?></a>
			<a class="docs button button-primary" href="<?php echo esc_url( apply_filters( 'compi_docs_url', Compi()->doc_url, COMPI_TEXT_DOMAIN ) ); ?>"><?php _e( 'Docs', COMPI_TEXT_DOMAIN ); ?></a>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo Compi()->web_url; ?>" data-text="<?php echo apply_filters( 'compi_welcome_twitter_username', __( 'Your tweet message would be placed here.', COMPI_TEXT_DOMAIN ) ); ?>" data-via="<?php echo Compi()->twitter_username; ?>" data-size="large" data-hashtags="<?php echo Compi()->name; ?>">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

			<div id="social-blocks">
				<div class="fb" >
					<div id="fb-root"></div>
					<script>
					(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
					</script>
					<div class="fb-like" data-href="http://www.facebook.com/<?php echo Compi()->facebook_page; ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>
				</div>

				<div class="twitter">
				<a href="https://twitter.com/<?php echo Compi()->twitter_username; ?>" class="twitter-follow-button" data-show-count="true" data-show-screen-name="false">Follow us</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</div>

				<div class="gplus">
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
				<g:plusone href="https://plus.google.com/<?php echo Compi()->google_plus_id; ?>" size="medium"></g:plusone>
				</div>
			</div>

		</p><!-- .compi-actions -->

		<h2 class="nav-tab-wrapper">
			<?php
			// Displays a tab for each dashboard page registered.
			foreach ($this->register_admin_menu() as $menu) {
				echo '<a class="nav-tab';
				if ( $_GET['page'] == $menu['id'] ) echo ' nav-tab-active';
				echo '" href=" ' . esc_url( admin_url( add_query_arg( array( 'page' => $menu['id'] ), 'index.php' ) ) ) . ' ">' . $menu['tab_name'] . '</a>';
			}
			?>
		</h2>
		<?php
	} // END intro()

	/**
	 * Output the about screen.
	 *

	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function about_screen() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<p class="about-description"><?php _e( 'Use this page to show what Compi does or what features you have added since your first release. Replace the placeholder images with screenshots of your plugin. You can even make the screenshots linkable to show a larger screenshot with or without caption or play an embedded video. It\'s all up to you.', COMPI_TEXT_DOMAIN ); ?></p>

			<div>
				<h3><?php _e( 'Three Columns with Screenshots', COMPI_TEXT_DOMAIN ); ?></h3>
				<div class="compi-feature feature-section col three-col">
					<div>
						<a href="http://placekitten.com/720/480" data-rel="prettyPhoto[gallery]"><img src="http://placekitten.com/300/250" alt="<?php _e( 'Screenshot Title', COMPI_TEXT_DOMAIN ); ?>" style="width: 99%; margin: 0 0 1em;"></a>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
					<div>
						<a href="http://placekitten.com/980/640" data-rel="prettyPhoto[gallery]" title="<?php _e( 'You can add captions to your screenshots.', COMPI_TEXT_DOMAIN ); ?>"><img src="http://placekitten.com/300/250" alt="" style="width: 99%; margin: 0 0 1em;"></a>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
					<div class="last-feature">
						<a href="http://vimeo.com/88671403" data-rel="prettyPhoto" title="<?php _e( 'Or add captions on your videos.', COMPI_TEXT_DOMAIN ); ?>"><img src="http://placekitten.com/300/250" alt="<?php _e( 'Video Title', COMPI_TEXT_DOMAIN ); ?>" style="width: 99%; margin: 0 0 1em;"></a>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
				</div>
			</div>

			<div>
				<h3><?php _e( 'Three Columns with a white background', COMPI_TEXT_DOMAIN ); ?></h3>
				<div class="compi-feature feature-section col three-col bg-white">
					<div>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
					<div>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
					<div class="last-feature">
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
				</div>
			</div>

			<div>
				<h3><?php _e( 'Two Columns with Screenshots', COMPI_TEXT_DOMAIN ); ?></h3>
				<div class="compi-feature feature-section col two-col">
					<div>
						<img src="http://placekitten.com/490/410" alt="" style="width: 99%; margin: 0 0 1em;">
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
					<div class="last-feature">
						<img src="http://placekitten.com/490/410" alt="" style="width: 99%; margin: 0 0 1em;">
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
				</div>
			</div>

			<div>
				<h3><?php _e( 'Two Columns with a Single Screenshot', COMPI_TEXT_DOMAIN ); ?></h3>
				<div class="compi-feature feature-section col two-col">
					<img src="http://placekitten.com/1042/600" alt="" style="width: 99%; margin: 0 0 1em;">
					<div>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
					<div class="last-feature">
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
				</div>
			</div>

			<div class="changelog">
				<h2 class="about-headline-callout"><?php _e( 'Callout Headline', COMPI_TEXT_DOMAIN ); ?></h2>
				<img src="http://placekitten.com/980/560" alt="" style="width: 99%; margin: 0 0 1em;">

				<div class="compi-feature feature-section col one-col center-col">
					<div>
						<h3><?php _e( 'One Column centered with a Single Screenshot', COMPI_TEXT_DOMAIN ); ?></h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
				</div>
			</div>

			<div>
				<div class="compi-feature feature-section col two-col">
					<div>
						<h3><?php _e( 'Two Columns, Content Left, Screenshot Right', COMPI_TEXT_DOMAIN ); ?></h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
						<h4><?php _e( 'Sub-Title (H4)', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
					<div class="last-feature">
						<img src="http://placekitten.com/526/394" alt="" style="width: 99%; margin: 0 0 1em;">
					</div>
				</div>
			</div>

			<div>
				<div class="compi-feature feature-section col two-col">
					<div>
						<img src="http://placekitten.com/526/394" alt="" style="width: 99%; margin: 0 0 1em;">
					</div>
					<div class="last-feature">
						<h3><?php _e( 'Two Columns, Content Right, Screenshot Left', COMPI_TEXT_DOMAIN ); ?></h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
						<h4><?php _e( 'Sub-Title (H4)', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
				</div>
			</div>

			<div>
				<h3><?php _e( 'Three Columns with NO Screenshots', COMPI_TEXT_DOMAIN ); ?></h3>

				<div class="compi-feature feature-section col three-col">
					<div>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>

					<div>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>

					<div class="last-feature">
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>
				</div>

				<div class="compi-feature feature-section col three-col">

					<div>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>

					<div>
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>

					<div class="last-feature">
						<h4><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
					</div>

				</div>

				<div class="compi-feature feature-section col three-col">
					<div>
						<h2 class="about-headline-callout"><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h2>
						<p class="about-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis.</p>
					</div>

					<div>
						<h2 class="about-headline-callout"><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h2>
						<p class="about-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis.</p>
					</div>

					<div class="last-feature">
						<h2 class="about-headline-callout"><?php _e( 'Title of Feature or New Changes', COMPI_TEXT_DOMAIN ); ?></h2>
						<p class="about-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis.</p>
					</div>

				</div>

			</div>

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => COMPI_PAGE . '-settings' ), 'admin.php' ) ) ); ?>"><?php _e( sprintf( 'Go to %s Settings', Compi()->name ), COMPI_TEXT_DOMAIN ); ?></a>
			</div>
		</div>
		<?php
	} // END about_screen()

	/**
	 * Output the changelog screen.
	 *

	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function changelog_screen() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<p><?php _e( 'Bulletpoint your changelog like so.', COMPI_TEXT_DOMAIN ); ?></p>

			<div class="changelog point-releases">
				<h3><?php _e( 'Version', COMPI_TEXT_DOMAIN ); ?> 1.0.1 (2<?php _e( 'nd March', COMPI_TEXT_DOMAIN ); ?> 2015)</h3>
				<ul>
					<li>Tested on WordPress 4 and up.</li>
					<li>Corrected spelling errors within the whole of the boilerplate and README.md file.</li>
					<li>Improved the use of PHPDoc conventions to document the code.</li>

					<li>Improved the System Report page and added a new filter for the status tabs.</li>
					<li>Removed 'Author Email', 'Requires at least' and 'Tested up to' from Compi header as they are not read by WordPress. These are mainly for the Readme.txt file.</li>
					<li>Moved the global $wpdb to the top of the uninstall.php file so both single and multisites can query the database.</li>
					<li>Removed the `countries` class and variables. - Can be added by following the documentation.</li>
					<li>Removed variable $theme_author_url. - Can be added by following the documentation.</li>
					<li>Removed variable $changelog_url. - Can be added by following the documentation.</li>
					<li>Removed 'after_setup_theme' action and setup_enviroment() function. - Can be added by following the documentation.</li>
					<li>Removed function set_admin_menu_separator(). This is no longer supported.</li>
					<li>Removed support for older versions of WordPress lower than version 3.8</li>
				</ul>

				<h3><?php _e( 'Version', COMPI_TEXT_DOMAIN ); ?> 1.0.1 (25<?php _e( 'th August', COMPI_TEXT_DOMAIN ); ?> 2014)</h3>
				<ul>
					<li>Grunt Setup</li>
					<li>Text Domain corrections</li>
					<li>Admin javascript minified</li>
					<li>README.md file updated</li>
				</ul>

				<h3><?php _e( 'Version', COMPI_TEXT_DOMAIN ); ?> 1.0.0 (25<?php _e( 'th August', COMPI_TEXT_DOMAIN ); ?> 2014)</h3>
				<p><strong><?php _e( sprintf( 'First version of the %s.', Compi()->name ), COMPI_TEXT_DOMAIN ); ?></strong></p>
			</div>

		</div>
		<?php
	} // END changelog_screen()

	/**
	 * Output the credits.
	 *

	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function credits_screen() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<p class="about-description"><?php _e( sprintf( 'The %s is developed and maintained by "S&eacute;bastien Dumont". Are you a passionate individual, would you like to give your support and see your name here? <a href="%s" target="_blank">Contribute to %s</a>.', Compi()->name, COMPI_GITHUB_REPO_URI . 'blob/master/CONTRIBUTING.md', Compi()->name ), COMPI_TEXT_DOMAIN ); ?></p>

			<div class="compi-feature feature-section col two-col">

				<div>
					<h2>S&eacute;bastien Dumont</h2>
					<h4 style="font-weight:0; margin-top:0"><?php _e( 'Project Leader &amp; Developer', COMPI_TEXT_DOMAIN ); ?></h4>
					<p><img style="float:left; margin: 0 15px 0 0;" src="<?php echo Compi()->plugin_url() . '/assets/images/sebd.jpg'; ?>" width="100" height="100" /><?php _e( 'I am a freelance WordPress Developer and I have been developing for WordPress since 2009. I provide Code Reviews, e-Commerce installations and custom WordPress plugin and theme development services. I developed this boilerplate and many others.', COMPI_TEXT_DOMAIN ); ?></p>
					<div class="compi-social-links">
						<a class="facebook_link" href="https://www.facebook.com/Sebd86" target="_blank">
							<span class="dashicons dashicons-facebook-alt"></span>
						</a>

						<a class="twitter_link" href="https://twitter.com/sebd86" target="_blank">
							<span class="dashicons dashicons-twitter"></span>
						</a>

						<a class="googleplus_link" href="https://plus.google.com/114016411970997366558" target="_blank">
							<span class="dashicons dashicons-googleplus"></span>
						</a>

					</div><!-- .compi-social-links -->
					<p><a href="http://www.sebastiendumont.com" target="_blank"><?php _e( sprintf( 'View %s&rsquo;s website', 'S&eacute;bastien' ), COMPI_TEXT_DOMAIN ); ?></a></p>
				</div>

				<div class="last-feature">
					<h2>Francois-Xavier B&eacute;nard</h2>
					<h4 style="font-weight:0; margin-top:0"><?php _e( 'Translation Manager, CEO of WP-Translations.org', COMPI_TEXT_DOMAIN ); ?></h4>
					<p><img style="float:left; margin: 0 15px 0 0;" src="<?php echo Compi()->plugin_url() . '/assets/images/fxbenard.jpg'; ?>" width="100" height="100" /><?php _e( 'Translation is my hobby, make it a living is my plan. I translate but also check and code the missing i18n() functions in your plugins or themes. I run a FREE WP Community of translators on Transifex. So if you need someone who cares about quality work, get in touch. Many developers are already trusting me, Seb of course but also Yoast, Pippin and the Mailpoet Team.', COMPI_TEXT_DOMAIN ); ?></p>
					<div class="compi-social-links">
						<a class="facebook_link" href="https://www.facebook.com/francoisxavier.benard" target="_blank">
							<span class="dashicons dashicons-facebook-alt"></span>
						</a>

						<a class="twitter_link" href="https://twitter.com/fxbenard" target="_blank">
							<span class="dashicons dashicons-twitter"></span>
						</a>

						<a class="googleplus_link" href="https://plus.google.com/115184248259085010066" target="_blank">
							<span class="dashicons dashicons-googleplus"></span>
						</a>

					</div><!-- .compi-social-links -->
					<p><a href="http://wp-translations.org" target="_blank"><?php _e( sprintf( 'View %s&rsquo;s website', 'Francois' ), COMPI_TEXT_DOMAIN ); ?></a></p>
				</div>

			</div>

			<hr class="clear" />

			<h4 class="wp-people-group"><?php _e( 'Contributers' , COMPI_TEXT_DOMAIN ); ?></h4><span style="color:#aaa; float:right; position:relative; top:-40px;"><?php _e( 'These contributers are fetched from the GitHub repository.', COMPI_TEXT_DOMAIN ); ?></span>

			<?php echo $this->contributors(); ?>

			<hr class="clear">

			<h4 class="wp-people-group"><?php _e( 'Translators' , COMPI_TEXT_DOMAIN ); ?> <span style="color:#aaa; float:right;"><?php _e( sprintf( 'These translators are fetched from the Transifex project for %s.', Compi()->name ), COMPI_TEXT_DOMAIN ); ?></span></h4>

			<p class="about-description"><?php _e( sprintf( '<strong>%s</strong> has been kindly translated into several other languages thanks to the WordPress community.', Compi()->name ), COMPI_TEXT_DOMAIN ); ?></p>
			<?php
			// Display all translators on the project with a link to their profile.
			transifex_display_translators();
			?>
			<p><?php _e( sprintf( 'Is your name not listed? Then how about taking part in helping with the translation of this plugin. See the list of <a href="%s">languages to translate</a>.', admin_url( 'index.php?page=' . COMPI_PAGE . '-translations' ) ), COMPI_TEXT_DOMAIN ); ?></p>

			<hr class="clear">

			<h4 class="wp-people-group"><?php _e( 'External Libraries' , COMPI_TEXT_DOMAIN ); ?></h4>
			<p class="wp-credits-list">
			<a href="http://jquery.com/" target="_blank">jQuery</a>,
			<a href="http://jqueryui.com/" target="_blank">jQuery UI</a>,
			<a href="http://malsup.com/jquery/block/" target="_blank">jQuery Block UI</a>,
			<a href="https://github.com/harvesthq/chosen" target="_blank">jQuery Chosen</a>,
			<a href="https://github.com/carhartl/jquery-cookie" target="_blank">jQuery Cookie</a>,
			<a href="http://code.drewwilson.com/entry/tiptip-jquery-plugin" target="_blank">jQuery TipTip</a> and
			<a href="http://www.no-margin-for-errors.com/projects/prettyPhoto-jquery-lightbox-clone/" target="_blank">prettyPhoto</a>
			</p>
		</div>
		<?php
	} // END credits_screen()

	/**
	 * Output the translations.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function translations_screen() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<p class="about-description"><?php _e( sprintf( 'Translations currently in progress and completed for %s. <a href="%s" target="_blank">View more on %s</a>.', Compi()->name, COMPI_TRANSIFEX_PROJECT_URI, 'Transifex' ), COMPI_TEXT_DOMAIN ); ?></p>

			<?php transifex_display_translation_progress(); ?>

		</div>
		<?php
	} // END translations_screen()

	/**
	 * Output the freedoms page.
	 *

	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function freedoms_screen() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<p class="about-description"><?php _e( 'Compi is Free and open source software, built to help speed up plugin development and to be stable and secure for all WordPress versions. Below is a list explaining what you are allowed to do. Enjoy!', COMPI_TEXT_DOMAIN ); ?></p>

			<ol start="1">
				<li><p><?php _e( 'You have the freedom to run the program, for any purpose.', COMPI_TEXT_DOMAIN ); ?></p></li>
				<li><p><?php _e( 'You have access to the source code, the freedom to study how the program works, and the freedom to change it to make it do what you wish.', COMPI_TEXT_DOMAIN ); ?></p></li>
				<li><p><?php _e( 'You have the freedom to redistribute copies of the original program so you can help your neighbor.', COMPI_TEXT_DOMAIN ); ?></p></li>
				<li><p><?php _e( 'You have the freedom to distribute copies of your modified versions to others. By doing this you can give the whole community a chance to benefit from your changes.', COMPI_TEXT_DOMAIN ); ?></p></li>
			</ol>
		</div>
		<?php
	} // END freedoms_screen()

	/**
	 * Render Contributors List
	 *
	 * @since  1.0.0
	 * @access public
	 * @filter compi_filter_contributors
	 * @return string $contributor_list HTML formatted list of contributors.
	 */
	public function contributors() {
		$contributors = $this->get_contributors();

		if ( empty( $contributors ) )
			return '';

		$contributor_list = '<ul class="wp-people-group">';

		$filtered_contributers = apply_filters( 'compi_filter_contributors', $contributors );

		foreach ( $contributors as $contributor ) {

			if ( !in_array( $contributor->login, $filtered_contributers ) ) {

				// Get details about this contributor.
				$contributor_details = $this->get_indvidual_contributor( $contributor->login );

				$contributor_list .= '<li class="wp-person">';
				$contributor_list .= sprintf( '<a href="%s" target="_blank" title="%s">',
					esc_url( 'https://github.com/' . $contributor->login ),
					esc_html( sprintf( __( 'View %s\'s GitHub Profile', COMPI_TEXT_DOMAIN ), $contributor_details->name ) )
				);
				$contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
				$contributor_list .= '</a>';

				if( isset( $contributor_details->name ) ) {
					$contributor_list .= __( 'Name', COMPI_TEXT_DOMAIN ) . ': <strong>' . htmlspecialchars( $contributor_details->name ) . '</strong><br>';
				}

				$contributor_list .= sprintf( __( 'Username', COMPI_TEXT_DOMAIN ) . ': <strong><a href="%s" target="_blank">%s</a></strong><br>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );

				if( isset( $contributor_details->blog ) ) {
					$contributor_list .= sprintf( '<strong><a href="%s" target="_blank">%s</a></strong><br>', esc_url( $contributor_details->blog ), __( 'View Website', COMPI_TEXT_DOMAIN ) );
				}

				$contributor_list .= '</li>';

			} // END if

		} // END foreach

		$contributor_list .= '</ul>';

		return $contributor_list;
	} // END contributors()

	/**
	 * Retrieve list of contributors from GitHub.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_contributors() {
		$contributors = get_transient( 'compi_contributors' );

		if ( false !== $contributors ) {
			return $contributors;
		}

		$response = wp_remote_get( 'https://api.github.com/repos/seb86/WordPress-Plugin-Boilerplate/contributors', array( 'sslverify' => false ) );

		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}

		$contributors = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! is_array( $contributors ) ) {
			return array();
		}

		set_transient( 'compi_contributors', $contributors, 3600 );

		return $contributors;
	} // END get_contributors()

	/**
	 * Retrieve details about the single contributor from GitHub.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $username
	 * @return mixed
	 */
	public function get_indvidual_contributor( $username ) {
		$contributor = get_transient( 'compi_' . $username . 'contributor' );

		if ( false !== $contributor ) {
			return $contributor;
		}

		$response = wp_remote_get( 'https://api.github.com/users/' . $username, array( 'sslverify' => false ) );

		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}

		$contributor = json_decode( wp_remote_retrieve_body( $response ) );

		set_transient( 'compi_' . $username . 'contributor', $contributor, 3600 );

		return $contributor;
	} // END get_indvidual_contributor()

	/**
	 * Sends user to the Welcome page on first activation of
	 * Compi as well as each time Compi is
	 * upgraded to a new version.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function welcome() {
		// Bail if no activation redirect transient is set
		if ( ! get_transient( '_compi_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_compi_activation_redirect' );

		// Bail if we are waiting to install or update via the interface update/install links
		if ( get_option( '_compi_needs_update' ) == 1 )
			return;

		// Bail if activating from network, or bulk, or within an iFrame
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) || defined( 'IFRAME_REQUEST' ) )
			return;

		if ( ( isset( $_GET['action'] ) && 'upgrade-plugin' == $_GET['action'] ) && ( isset( $_GET['plugin'] ) && strstr( $_GET['plugin'], 'wordpress-plugin-boilerplate.php' ) ) )
			return;

		wp_redirect( admin_url( 'index.php?page=' . COMPI_PAGE . '-about' ) );
		exit;
	} // END welcome()

} // end class.

} // end if class exists.

new Compi_Admin_Welcome();

?>
