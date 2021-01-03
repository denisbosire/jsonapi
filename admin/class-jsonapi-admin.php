<?php
//require_once plugin_dir_url( __DIR__ ) . 'vendor/autoload.php';
require( dirname( __FILE__ ) . '/../vendor/autoload.php');

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
class Jsonapi_Admin {
//use GuzzleHttp\Client;
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		//$this->getClient();
		//$this->jsonapi_page();


	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jsonapi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jsonapi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jsonapi-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jsonapi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jsonapi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'jsonapi', plugin_dir_url( __FILE__ ) . 'js/jsonapi-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			'jsonapi',
			'jsonapi',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce('ajax-nonce')
			)
		);

	}

	// Register the menu.
	public function jsonapi_page_settings() {
			add_menu_page( 'My JsonAPI', 'Json API', 'manage_options', 'jsonapi', array(__CLASS__,'jsonapi_page'), 'dashicons-tickets', 6  );
	}

	// Print the markup for the page
	public function jsonapi_page() {
	   // if ( !current_user_can( "manage_options" ) )  {
	   //    wp_die( __( "You do not have sufficient permissions to access this page." ) );
	   // }
	   //getClient();
	   echo '<h2>Json API Page</h2>';
		$client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => 'https://jsonplaceholder.typicode.com/',
		]);
		  
		$response = $client->request('GET', '/users', [
		    'query' => [
		        'page' => '2',
		    ]
		]);
		 
		//get status code using $response->getStatusCode();
		 
		$body = $response->getBody();
		$arr_body = json_decode($body, true);
		?>
		<div id="usercontent">
			<div class="postlist"></div>
			<div class="todos"></div>
			<div class="albums">
				
			</div>
		</div>
		
		<div id="content-area">

		<table style="width:100%"; class="data-table">
		 	<thead>
		        <tr style="text-align:left;">
		            <th><?php esc_html_e('User ID:','jsonapi');?></th> 
		            <th><?php esc_html_e('Name:','jsonapi');?></th> 
		            <th><?php esc_html_e('Email:','jsonapi');?></th> 
		            <th><?php esc_html_e('City:','jsonapi');?></th> 
		            <th><?php esc_html_e('Phone:','jsonapi');?></th> 
		            <th><?php esc_html_e('Website:','jsonapi');?></th> 
		            <th><?php esc_html_e('Company:','jsonapi');?></th>

		        </tr>
		    </thead>
		        <?php foreach ($arr_body as $value)  {    ?>
		            <tr>
		                <td><a href="#" data-userid="<?php echo $value['id'];?>" class="userid"><?php echo $value['id']; ?> </a></td> 
		                <td><?php echo $value['name']; ?> </td> 
		                <td><?php echo $value['email']; ?> </td>
		                <td><?php echo $value['address']['city']; ?> </td>
		                <td><?php echo $value['phone']; ?> </td>
		                <td><?php echo $value['website']; ?> </td>
		                <td><?php echo $value['company']['name']; ?> </td>
		            </tr>
		        <?php  } ?>
		</table>	
		</div>	 
		<?php
	
	}




	public function get_user_posts(){
		$client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => 'https://jsonplaceholder.typicode.com/',
		]);
		  
		$response = $client->request('GET', '/posts', [
		    'query' => [
		        'page' => '1',
		    ]
		]);	
		 
		$body = $response->getBody();
		$arr_body = json_decode($body, true);
		$userID = $_GET["userid"];
		$userPosts = [];
		$postID = [];

		foreach ($arr_body as $key => $value) {
			if($userID == $value['userId']){
				$userPosts[] = $value;
				$postID[] = $value['id'];
			}
		}
		//var_export($userPosts);
		wp_send_json_success( $userPosts );
		
	}

	public function get_user_todos(){
		$client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => 'https://jsonplaceholder.typicode.com',
		]);
		  
		$response = $client->request('GET', '/todos', [
		    'query' => [
		        'page' => '1',
		    ]
		]);	
		 
		$body = $response->getBody();
		$arr_body = json_decode($body, true);
		$userID = $_GET["userid"];
		$userTodo = [];

		foreach ($arr_body as $key => $value) {
			if($userID == $value['userId']){
				$userTodo[] = $value;
			}
		}
		//var_export($userPosts);
		wp_send_json_success( $userTodo );

		
	}
	public function get_user_albums(){
		$client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => 'https://jsonplaceholder.typicode.com',
		]);
		  
		$response = $client->request('GET', '/albums', [
		    'query' => [
		        'page' => '1',
		    ]
		]);	
		 
		$body = $response->getBody();
		$arr_body = json_decode($body, true);
		$userID = $_GET["userid"];
		$albums = [];
		$albumID = [];

		foreach ($arr_body as $key => $value) {
			if($userID == $value['userId']){
				$albums[] = $value;
				$album['id'] = $albumID;
			}
		}
		//var_export($userPosts);
		wp_send_json_success( $albums );
		
	}

	public function get_photos(){
		$client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => 'https://jsonplaceholder.typicode.com',
		]);
		  
		$response = $client->request('GET', '/photos', [
		    'query' => [
		        'page' => '1',
		    ]
		]);	
		 
		$body = $response->getBody();
		$arr_body = json_decode($body, true);
		$albumID = $_GET["albumid"];
		$photos = [];

		foreach ($arr_body as $key => $value) {
			if($albumID == $value['albumId']){
				$photos[] = $value;
				//$album['id'] = $albumID
			}
		}
		//var_export($photos);
		wp_send_json_success( $photos );
		
	}
	public function get_comments(){
		$client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => 'https://jsonplaceholder.typicode.com',
		]);
		  
		$response = $client->request('GET', '/comments', [
		    'query' => [
		        'page' => '1',
		    ]
		]);	
		 
		$body = $response->getBody();
		$arr_body = json_decode($body, true);
		$postID = $_GET["postid"];
		$comments = [];

		foreach ($arr_body as $key => $value) {
			if($postID == $value['postId']){
				$comments[] = $value;
				//$album['id'] = $albumID
			}
		}
		//var_export($comments);
		wp_send_json_success( $comments );
		
	}

}
