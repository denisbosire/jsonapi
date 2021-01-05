<?php
// require_once plugin_dir_url( __DIR__ ) . 'vendor/autoload.php';
require dirname( __FILE__ ) . '/../vendor/autoload.php';
// loca the class
use GuzzleHttp\Client;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://thepixeltribe.com
 * @since      1.0.0
 *
 * @package    Jsonapi
 * @subpackage Jsonapi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jsonapi
 * @subpackage Jsonapi/admin
 * @author     Denis Bosire <denischweya@gmail.com>
 */
class GetApi {
	// use GuzzleHttp\Client;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// $this->getClient();
		// $this->jsonapi_page();
	}
	// Load CSS
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'public/css/jsonapi-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area, also localizes the scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'jsonapi', plugin_dir_url( __DIR__ ) . 'public/js/jsonapi-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			'jsonapi',
			'jsonapi',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax-nonce' ),
			)
		);

	}

	// Print the markup for the page
	public function jsonapi_page() {
		// this is the initial table for users
		echo '<h2 class="page-title">Json API Page</h2>';
		echo '<div id="loader" class="lds-dual-ring hidden overlay"></div>';
		// Could use as well, wp_remote_get
		$client = new Client(
			array(
				// Base URI is used with relative requests
				'base_uri' => 'https://jsonplaceholder.typicode.com/',
			)
		);
		// use guzzle to fetch data, could also use wp_remote_request()
		$response = $client->request(
			'GET',
			'/users',
			array(
				'query' => array(
					'page' => '2',
				),
			)
		);

		// get status code using $response->getStatusCode();

		$body     = $response->getBody();
		$arr_body = json_decode( $body, true );
		// start creating the layout
		?>
		<div id="usercontent">
			<a href="#" class="bckbtn"><span class="dashicons dashicons-arrow-left-alt"><?php esc_html_e( 'Back', 'jsonapi' ); ?></span></a>

			<div class="ajax-data">
				<div class="postlist"></div>
				<div class="todos"></div>
				<div class="albums">
			</div> 
			</div>
		</div>
		
		<div id="content-area">

		<table class="data-table">
			<thead>
				<tr style="text-align:left;">
					<th><?php esc_html_e( 'User ID:', 'jsonapi' ); ?></th> 
					<th><?php esc_html_e( 'Name:', 'jsonapi' ); ?></th> 
					<th><?php esc_html_e( 'Email:', 'jsonapi' ); ?></th> 
					<th><?php esc_html_e( 'Username:', 'jsonapi' ); ?></th> 
					<th><?php esc_html_e( 'City:', 'jsonapi' ); ?></th> 
					<th><?php esc_html_e( 'Phone:', 'jsonapi' ); ?></th> 
					<th><?php esc_html_e( 'Website:', 'jsonapi' ); ?></th> 
					<th><?php esc_html_e( 'Company:', 'jsonapi' ); ?></th>

				</tr>
			</thead>
				<?php foreach ( $arr_body as $value ) { ?>
					<tr>
						<td><a href="#" data-userid="<?php echo $value['id']; ?>" class="userid"><?php echo $value['id']; ?> </a></td> 
						<td><a href="#" data-userid="<?php echo $value['id']; ?>" class="userid"><?php echo $value['name']; ?></a></td> 
						<td><a href="#" data-userid="<?php echo $value['id']; ?>" class="userid"><?php echo $value['email']; ?></a></td>
						<td><a href="#" data-userid="<?php echo $value['id']; ?>" class="userid"><?php echo $value['username']; ?></a></td>
						<td><?php echo $value['address']['city']; ?> </td>
						<td><?php echo $value['phone']; ?> </td>
						<td><?php echo $value['website']; ?> </td>
						<td><?php echo $value['company']['name']; ?> </td>
					</tr>
				<?php } ?>
		</table>    
		</div>   
		<?php

	}

	public function get_user_posts() {
		// refactor the code below, it repeats several times
		$client = new Client(
			array(
				// Base URI is used with relative requests
				'base_uri' => 'https://jsonplaceholder.typicode.com/',
			)
		);

		$response = $client->request(
			'GET',
			'/posts',
			array(
				'query' => array(
					'page' => '1',
				),
			)
		);

		$body     = $response->getBody();
		$arr_body = json_decode( $body, true );
		// set userID
		$userID    = $_GET['userid'];
		$userPosts = array();
		$postID    = array();

		foreach ( $arr_body as $key => $value ) {
			if ( $userID == $value['userId'] ) {
				$userPosts[] = $value;
				$postID[]    = $value['id'];
			}
		}
		// sends the data to be ajax'd
		wp_send_json_success( $userPosts );

	}

	public function get_user_todos() {
		// refactor the code below, it repeats several times
		$client = new Client(
			array(
				// Base URI is used with relative requests
				'base_uri' => 'https://jsonplaceholder.typicode.com',
			)
		);

		$response = $client->request(
			'GET',
			'/todos',
			array(
				'query' => array(
					'page' => '1',
				),
			)
		);

		$body     = $response->getBody();
		$arr_body = json_decode( $body, true );
		$userID   = $_GET['userid'];
		$userTodo = array();

		foreach ( $arr_body as $key => $value ) {
			if ( $userID == $value['userId'] ) {
				$userTodo[] = $value;
			}
		}
		// var_export($userPosts);
		wp_send_json_success( $userTodo );

	}
	public function get_user_albums() {
		// refactor the code below, it repeats several times
		$client = new Client(
			array(
				// Base URI is used with relative requests
				'base_uri' => 'https://jsonplaceholder.typicode.com',
			)
		);

		$response = $client->request( 'GET', '/albums' );

		$body     = $response->getBody();
		$arr_body = json_decode( $body, true );
		$userID   = $_GET['userid'];
		$albums   = array();
		$albumID  = array();

		foreach ( $arr_body as $key => $value ) {
			if ( $userID == $value['userId'] ) {
				$albums[]    = $value;
				$album['id'] = $albumID;
			}
		}

		wp_send_json_success( $albums );

	}

	public function get_photos() {
		$client = new Client(
			array(
				// Base URI is used with relative requests
				'base_uri' => 'https://jsonplaceholder.typicode.com',
			)
		);

		$response = $client->request(
			'GET',
			'/photos',
			array(
				'query' => array(
					'page' => '1',
				),
			)
		);

		$body     = $response->getBody();
		$arr_body = json_decode( $body, true );
		// set albumID, to be used for photos
		$albumID = $_GET['albumid'];
		$photos  = array();

		foreach ( $arr_body as $key => $value ) {
			if ( $albumID == $value['albumId'] ) {
				$photos[] = $value;
				// $album['id'] = $albumID
			}
		}
		wp_send_json_success( $photos );

	}
	public function get_comments() {
		$client = new Client(
			array(
				// Base URI is used with relative requests
				'base_uri' => 'https://jsonplaceholder.typicode.com',
			)
		);

		$response = $client->request(
			'GET',
			'/comments',
			array(
				'query' => array(
					'page' => '1',
				),
			)
		);

		$body     = $response->getBody();
		$arr_body = json_decode( $body, true );
		$postID   = $_GET['postid'];
		$comments = array();

		foreach ( $arr_body as $key => $value ) {
			if ( $postID == $value['postId'] ) {
				$comments[] = $value;
				// $album['id'] = $albumID
			}
		}
		wp_send_json_success( $comments );

	}
	// set the custom_url, *must be a better way to do this
	public function my_custom_url_handler() {
		// wp
		if ( $_SERVER['REQUEST_URI'] == '/custom_url' ) {
			// $this->jsonapi_page();
			$template = plugin_dir_url( __DIR__ ) . 'templates/custom-url-template.php';
			// exit();
		}
	}
}
// $getapi = new GetApi();
