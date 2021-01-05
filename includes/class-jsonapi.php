<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://thepixeltribe.com
 * @since      1.0.0
 *
 * @package    Jsonapi
 * @subpackage Jsonapi/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Jsonapi
 * @subpackage Jsonapi/includes
 * @author     Denis Bosire <denischweya@gmail.com>
 */
class Jsonapi {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Jsonapi_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'JSONAPI_VERSION' ) ) {
			$this->version = JSONAPI_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'jsonapi';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_getapi_hooks();
		$this->define_template_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Jsonapi_Loader. Orchestrates the hooks of the plugin.
	 * - Jsonapi_i18n. Defines internationalization functionality.
	 * - Jsonapi_Admin. Defines all hooks for the admin area.
	 * - Jsonapi_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jsonapi-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jsonapi-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-jsonapi-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-jsonapi-public.php';
		/**
		 * Template for the restapi page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jsonapi-template.php';
		/**
		 * Get API class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jsonapi-getapi.php';

		$this->loader = new Jsonapi_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Jsonapi_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Jsonapi_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Jsonapi_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}
	/**
	 * Register all of the hooks related to the get api class
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_getapi_hooks() {

		$getapi = new Get_Rest_Api( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_styles', $getapi, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $getapi, 'enqueue_scripts' );
		$this->loader->add_action( 'parse_request', $getapi, 'my_custom_url_handler' );
		// WP AJAX
		$this->loader->add_action( 'wp_ajax_nopriv_get_user_posts', $getapi, 'get_user_posts' );
		$this->loader->add_action( 'wp_ajax_get_user_posts', $getapi, 'get_user_posts' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_user_todos', $getapi, 'get_user_todos' );
		$this->loader->add_action( 'wp_ajax_get_user_todos', $getapi, 'get_user_todos' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_user_albums', $getapi, 'get_user_albums' );
		$this->loader->add_action( 'wp_ajax_get_user_albums', $getapi, 'get_user_albums' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_photos', $getapi, 'get_photos' );
		$this->loader->add_action( 'wp_ajax_get_photos', $getapi, 'get_photos' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_comments', $getapi, 'get_comments' );
		$this->loader->add_action( 'wp_ajax_get_comments', $getapi, 'get_comments' );
		// Add action, use this hook in the template
		$this->loader->add_action( 'jsonapi_init', $getapi, 'jsonapi_page', 7 );
	}
	/**
	 * Register all of the hooks related to the custom template functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_template_hooks() {
		$template = new Template_Loader( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'init', $template, 'jsonapi_rewite' );
		$this->loader->add_filter( 'query_vars', $template, 'jsonapi_custom_query_vars' );
		$this->loader->add_filter( 'template_include', $template, 'jsonapi_custom_template', 99 );
		// add dashicons, not automatically loaded in custom templates
		$this->loader->add_action( 'wp_enqueue_scripts', $template, 'jsonapi_load_dashicons' );

	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Jsonapi_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Jsonapi_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
