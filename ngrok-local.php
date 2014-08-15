<?php
/**
 * Plugin Name: Ngrok Local
 * Plugin URI: https://wp-stream.com/
 * Description: Translate host on the fly to expose local server to the web using ngrok.
 * Version: 0.0.1
 * Author: Jonathan Bardo
 * Author URI: http://jonathanbardo.com
 * License: GPLv2+
 */

class Ngrok_Local {

	private $site_url;

	public function __construct(){
		$this->site_url = site_url() . '/';;

		if ( ! defined( 'WP_SITEURL' ) && ! defined( 'WP_HOME' ) && isset( $_SERVER['HTTP_HOST'] ) ) {
			define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] );
			define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );
		} else {
			// bail if those constant are already defined
			return false;
		}

		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
	}

	public function template_redirect() {
		if ( ! isset( $_GET['wp_ngrok_autoload'] ) ) {
			$protocol = is_ssl() ? 'https://' : 'http://';
			echo str_replace(
				$this->site_url,
				wp_make_link_relative( $this->site_url ),
				file_get_contents( add_query_arg( 'wp_ngrok_autoload', 1, $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ) )
			);
			exit;
		}
	}
}

new Ngrok_Local;
