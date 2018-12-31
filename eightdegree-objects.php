<?php
/**
 * Plugin Name: EightDegree Objects
 * Description: An addition to elementor objects with themes developed by 8DegreeThemes.
 * Plugin URI:  https://8degreethemes.com/plugins/eightdegree-objects/
 * Version:     1.0.0
 * Author:      8DegreeThemes
 * Author URI:  https://8degreethemes.com/
 * Text Domain: eightdegree-objects
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin Directory Path
 *
 * @since 1.0.0
 *
 * @var string The plugin path.
 */
defined( 'EDO_DIR_PATH' ) or define( 'EDO_DIR_PATH', plugin_dir_path( __FILE__ ) );
/**
 * Main EightDegree Objects Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class EightDegree_Objects {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';
	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var EightDegree_Objects The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return EightDegree_Objects An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'eightdegree-objects' );

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
			// Register Widget Scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
			//Register Widget Categories
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_categories' ] );

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		//add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 999 );

		add_action( 'elementor/frontend/after_register_scripts', function() {
			wp_register_script( 'elementor-the100-script', plugins_url( '/assets/js/the100-elementor-script.js', __FILE__ ), [ 'jquery' ], false, true );
		} );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'EightDegree Objects', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'EightDegree Objects', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'EightDegree Objects', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-test-extension' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function widget_styles() {

		wp_register_style( 'the100-elementor-design', plugins_url( 'assets/css/the100-elementor-design.css', __FILE__ ) );

	}
	public function widget_scripts() {

		wp_register_script( 'the100-elementor-script', plugins_url('/assets/js/the100-elementor-script.js' ,  __FILE__), [ 'jquery', 'the100-owl' ] );

	}

	/**
	 * Enqueue scripts
	 *
	 * @since   1.0.0
	 */
	public function scripts() {

		// Load main stylesheet
		//wp_enqueue_style('the100-owl', plugins_url( '/css/owl.carousel.css', __FILE__ ) );
		//wp_enqueue_style('the100-owl-theme', plugins_url( '/css/owl.theme.default.css', __FILE__ ) );	
		wp_enqueue_style( 'the100-elementor-design', plugins_url( '/assets/css/the100-elementor-design.css', __FILE__ ) );

		//wp_enqueue_script( 'the100-owl', plugins_url( '/assets/js/owl.carousel.js', __FILE__ ) );
		// Load main script
		wp_enqueue_script( 'the100-elementor-script', plugins_url( '/assets/js/the100-elementor-script.js', __FILE__ ) );

	}
	function widget_categories( $elements_manager ) {
		//eicons library https://pojome.github.io/elementor-icons/
		$elements_manager->add_category(
			'8degreethemes',
			[
				'title' => __( '8Degree Themes', 'the100' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files
		require_once( __DIR__ . '/widgets/main-slider.php' );
		require_once( __DIR__ . '/widgets/promo-page.php' );
		require_once( __DIR__ . '/widgets/featured-section.php' );
		require_once( __DIR__ . '/widgets/team-section.php' );
		require_once( __DIR__ . '/widgets/gallery-section.php' );
		require_once( __DIR__ . '/widgets/aboutservice-section.php' );
		require_once( __DIR__ . '/widgets/testimonial-section.php' );
		require_once( __DIR__ . '/widgets/blog-settings.php' );
		
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_Main_Slider_Widget() );
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_Promo_Page_Widget() );
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_Featured_Section_Widget() );
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_Team_Section_Widget() );
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_Gallery_Section_Widget() );
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_About_Section_Widget() );
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_Testimonial_Section_Widget() );
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_Blog_Section_Widget() );
		
		
		if(class_exists('woocommerce')){
			require_once( __DIR__ . '/widgets/products-grid.php' );
			require_once( __DIR__ . '/widgets/product-categories.php' );
			require_once( __DIR__ . '/widgets/product-slider.php' );
			require_once( __DIR__ . '/widgets/product-tabbed.php' );
			// Register widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_Product_Section_Widget() );
			//Register Widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_ProductCat_Section_Widget() );
			//Register Widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_ProductSlider_Section_Widget() );
			//Register Widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_The100_ProductTabbed_Section_Widget() );
		}
	}

	/**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	/*public function init_controls() {

		// Include Control files
		require_once( __DIR__ . '/controls/test-control.php' );

		// Register control
		\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );

	}*/

}

EightDegree_Objects::instance();

function the100_elementor_category_lists(){
	$category 	=	get_categories( array(
		'hide_empty' => 1,
		'orderby' => 'name',
		'parent'  => 0,
	));
	$cat_list 	=	array();
	$cat_list[0]=	__('Select Parent category','the100-pro');
	foreach ($category as $cat) {
		$cat_list[$cat->term_id]	=	$cat->name;
	}
	return $cat_list;
}

function the100_elementor_page_lists(){
	$pages 	=	get_pages( array(
		'post_status' => 'publish',
		'parent'  => 0,
	));
	$page_list 	=	array();
	$page_list[0]=	__('Select Pages','the100-pro');
	foreach ($pages as $page) {
		$page_list[$page->ID]	=	$page->post_title;
	}
	return $page_list;
}

/*
function eightdegree_objects_plugin_path() {

  // gets the absolute path to this plugin directory

	return untrailingslashit( plugin_dir_path( __FILE__ ) );
}
add_filter( 'woocommerce_locate_template', 'eightdegree_objects_woocommerce_locate_template', 10, 3 );
function eightdegree_objects_woocommerce_locate_template( $template, $template_name, $template_path ) {
	global $woocommerce;

	$_template = $template;

	if ( ! $template_path ) $template_path = $woocommerce->template_url;

	$plugin_path  = eightdegree_objects_plugin_path() . '/woocommerce/';

  // Look within passed path within the theme - this is priority
	$template = locate_template(

		array(
			$template_path . $template_name,
			$template_name
		)
	);

  // Modification: Get the template from this plugin, if it exists
	if ( ! $template && file_exists( $plugin_path . $template_name ) )
		$template = $plugin_path . $template_name;

  // Use default template
	if ( ! $template )
		$template = $_template;

  // Return what we found
	return $template;
}
*/