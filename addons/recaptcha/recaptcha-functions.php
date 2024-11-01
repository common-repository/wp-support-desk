<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Adding reCAPTCHA before user registration
add_action('wpneo_before_user_registration_action', 'wpneo_before_user_registration_action');
add_action('wpsd_before_login_action', 'wpsd_before_login_action');
add_action('wpsd_before_registration_action', 'wpsd_before_registration_action');
add_action('wpsd_before_ticket_create_action', 'wpsd_before_ticket_create_action');


if ( ! function_exists('wpsd_recaptcha_options')){
	function wpsd_recaptcha_options($option_name) {
		$wpsd_options = get_option('wpsd_recaptcha_options');
		if ($wpsd_options){
			if ( array_key_exists($option_name, $wpsd_options))
				return $wpsd_options[$option_name];
		}
		return null;
	}
}


if ( ! empty($_POST['wpsd_recaptcha_settings_save_btn'])){
	if (wp_verify_nonce( sanitize_text_field(wpsd_post('wpsd_settings_page_nonce_field')), 'wpsd_settings_page_action' ) ){

		$wpsd_enable_recaptcha = sanitize_text_field(wpsd_post('wpsd_enable_recaptcha'));
		$wpsd_recaptcha_site_key = sanitize_text_field(wpsd_post('wpsd_recaptcha_site_key'));
		$wpsd_recaptcha_secret_key = sanitize_text_field(wpsd_post('wpsd_recaptcha_secret_key'));

		$wpsd_enable_recaptcha_in_login_form = sanitize_text_field(wpsd_post('wpsd_enable_recaptcha_in_login_form'));
		$wpsd_enable_recaptcha_in_registration_form = sanitize_text_field(wpsd_post('wpsd_enable_recaptcha_in_registration_form'));
		$wpsd_enable_recaptcha_in_ticekt_creating_form = sanitize_text_field(wpsd_post('wpsd_enable_recaptcha_in_ticekt_creating_form'));

		$data_array = array(
			'wpsd_enable_recaptcha'             			=> $wpsd_enable_recaptcha,
			'wpsd_recaptcha_site_key'  						=> $wpsd_recaptcha_site_key,
			'wpsd_recaptcha_secret_key'     				=> $wpsd_recaptcha_secret_key,

			'wpsd_enable_recaptcha_in_login_form'			=> $wpsd_enable_recaptcha_in_login_form,
			'wpsd_enable_recaptcha_in_registration_form'	=> $wpsd_enable_recaptcha_in_registration_form,
			'wpsd_enable_recaptcha_in_ticekt_creating_form'	=> $wpsd_enable_recaptcha_in_ticekt_creating_form,
		);

		update_option('wpsd_recaptcha_options', $data_array);

		wpsd_flash( __('Settings has been saved', 'wp-support-desk'), 'success');
		wpsd_redirect_back();
	}
}


if ( ! function_exists('wpneo_before_user_registration_action')) {
	function wpneo_before_user_registration_action() {
		if (get_option('wpneo_enable_recaptcha_in_user_registration') == 'true') {
			if (function_exists('wpsd_checking_recaptcha_api')){
				wpsd_checking_recaptcha_api();
			}
		}
	}
}

if ( ! function_exists('wpsd_before_login_action')) {
	function wpsd_before_login_action() {
		if (wpsd_recaptcha_options('wpsd_enable_recaptcha_in_registration_form') == 1) {
			if (function_exists('wpsd_checking_recaptcha_api')){
				wpsd_checking_recaptcha_api();
			}
		}
	}
}

if ( ! function_exists('wpsd_before_registration_action')) {
	function wpsd_before_registration_action() {
		if (wpsd_recaptcha_options('wpsd_enable_recaptcha_in_login_form') == 1) {
			if (function_exists('wpsd_checking_recaptcha_api')){
				wpsd_checking_recaptcha_api();
			}
		}
	}
}

if ( ! function_exists('wpsd_before_ticket_create_action')) {
	function wpsd_before_ticket_create_action() {
		if (wpsd_recaptcha_options('wpsd_enable_recaptcha_in_ticekt_creating_form') == 1) {
			if (function_exists('wpsd_checking_recaptcha_api')){
				wpsd_checking_recaptcha_api();
			}
		}
	}
}



/**
 * Checking recaptcha through api
 */
if ( ! function_exists('wpsd_checking_recaptcha_api')) {
	function wpsd_checking_recaptcha_api(){

		if (wpsd_recaptcha_options('wpsd_enable_recaptcha') == '1') {

			$recaptcha_return = (object) array('success' => false);
			if ( ! empty($_POST['g-recaptcha-response'])) {

				if ( get_option('wpsd_recaptcha_success') == 'false' ) {

					$wpsd_recaptcha_secret_key = wpsd_recaptcha_options('wpsd_recaptcha_secret_key');
					$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$wpsd_recaptcha_secret_key.'&response='.$_POST['g-recaptcha-response']);
					$recaptcha_return = json_decode($verifyResponse);

				}elseif(get_option('wpsd_recaptcha_success') == 'true') {
					$recaptcha_return = (object) array('success' => true);
				}

			}
			if ( ! $recaptcha_return->success) {
				update_option('wpsd_recaptcha_success', 'false');
				die(json_encode(array('success'=> 0, 'msg' => __('Error with reCAPTCHA, please check again', 'wp-support-desk'))));
			}else{
				update_option('wpsd_recaptcha_success', 'true');
			}
		}
	}
}