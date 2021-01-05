<?php
class Template_Loader {



	public function jsonapi_rewite() {
		add_rewrite_rule( 'custom_url/(\d*)$', 'index.php?custom_url', 'top' );
	}

	public function jsonapi_custom_query_vars( $query_vars ) {
		$query_vars[] = 'custom_url';
		return $query_vars;
		var_dump( $query_vars );
	}
	public function jsonapi_custom_template( $template ) {
		if ( $_SERVER['REQUEST_URI'] == '/custom_url' ) {
			// load the file if exists
			$template = plugin_dir_url( __DIR__ ) . 'templates/custom-url-template.php';

		} else {
			// die();
		}

		return $template;

	}
	public function jsonapi_load_dashicons() {
		wp_enqueue_style( 'dashicons' );
	}
}
