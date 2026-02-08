<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Triply_Login' ) ) :
	class Triply_Login {
		public function __construct() {
			add_action( 'wp_ajax_triply_login', array( $this, 'ajax_login' ) );
			add_action( 'wp_ajax_nopriv_triply_login', array( $this, 'ajax_login' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
		}

		public function scripts(){
			global $triply_version;
			wp_enqueue_script( 'triply-ajax-login', get_template_directory_uri() . '/assets/js/frontend/login.js', array('jquery'), $triply_version, true );
		}

		public function ajax_login() {
			do_action( 'triply_ajax_verify_captcha' );
			check_ajax_referer( 'ajax-triply-login-nonce', 'security-login' );
			$info                  = array();
			$info['user_login']    = $_REQUEST['username'];
			$info['user_password'] = $_REQUEST['password'];
			$info['remember']      = $_REQUEST['remember'];

			$user_signon = wp_signon( $info, false );
			if ( is_wp_error( $user_signon ) ) {
				wp_send_json( array(
					'status' => false,
					'msg'    => esc_html__( 'Wrong username or password. Please try again!!!', 'triply' )
				) );
			} else {
				wp_set_current_user( $user_signon->ID );
				wp_send_json( array(
					'status' => true,
					'msg'    => esc_html__( 'Signin successful, redirecting...', 'triply' )
				) );
			}
		}
	}
new Triply_Login();
endif;
