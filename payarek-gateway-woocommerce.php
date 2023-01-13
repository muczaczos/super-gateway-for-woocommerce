<?php

/** 
* Plugin Name: Payarek Payments Gateway
* Plugin URI: https://grzybole.pl
* Author: Arek Szatkowski UiTeH
* Author URI: https://grzybole.pl/
* Description: Paymants Gateway for mobile
* Version: 1.0.0
* License: GPL2
* Text Domain: payarek-woo
*/

/**
 * Class WC_Gateway_Payarek file.
 *
 * @package WooCommerce\Payarek
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Payarek mobile payment Gateway.
 *
 * Provides a Payarek mobile Payment Gateway.
 *
 * @class       WC_Gateway_Payarek
 * @extends     WC_Payment_Gateway
 * @version     1.0.0
 * @package     WooCommerce\Classes\Payment
 */
if ( ! in_array('woocommerce/woocommerce.php', apply_filters(
	'active_plugins', get_option('active_plugins')))) return;


	function wpdocs_register_my_custom_menu_page(){
		add_menu_page(
			__('Payarek plugin settings', 'payarek-woo'),  //Menu title and textdomain that is help with translation
			'Payarek plugin',										//Title which be displayed on setings page
			'manage_options', 									//Only users with admistrative rights can use that menu	
			'payarek-plugin.php',								//menu slug
			'create_menu_page',									//callable function
			'dashicons-testimonial',						//menu icon
			85 																	//Order, sequence in the menu
		);
	}
	add_action('admin_menu', 'wpdocs_register_my_custom_menu_page');

	function create_menu_page(){
		$url = 'https://jsonplaceholder.typicode.com/users';

		$arguments = array(
			'method' => 'GET'
		);

		$response = wp_remote_get($url, $arguments);

		if (200 == wp_remote_retrieve_response_code($response)){

			$file_link = WP_PLUGIN_DIR . '/super-gateway-for-woocommerce/data.json';

			//echo '<pre>';
			//var_dump(wp_remote_retrieve_body($response));
			//echo '</pre>';
			$message = wp_remote_retrieve_body($response);
			write_to_file($message, $file_link);
		}

		if (is_wp_error($response)){

			$file_link = WP_PLUGIN_DIR . '/super-gateway-for-woocommerce/error-log.txt';

			$error_message = $response->get_error_message();
			$error_message = date('d M Y g:i:a') . ' - ' . $error_message;
			write_to_file($error_message, $file_link);
		}

		
	}

	function write_to_file($message, $file_link){
		
		if(file_existS($file_link)){
			$file = fopen($file_link, 'a');   //If file exist append content to the file
			fwrite($file, $message . "\n" );
		}else{
			$file = fopen($file_link, 'w');   //If file not exist create file and write to the file
			fwrite($file, $message . "\n" );
		}
		fclose($file);
	}

	//add_action('admin_init','callback_function_name');
	function callback_function_name(){

		
	}
	
	add_action('plugins_loaded', 'payarek_payment_init', 11);
	
	function payarek_payment_init() {
			if(class_exists('WC_PAYMENT_GATEWAY')) {
				require_once plugin_dir_path(__FILE__) . '/includes/class-wc-payment-gateway-payarek.php';
				require_once plugin_dir_path(__FILE__) . '/includes/payarek-order-statuses.php';
				require_once plugin_dir_path(__FILE__) . '/includes/payarek-checkout-desctiption-fields.php';
			}
		}

	add_filter('woocommerce_payment_gateways', 'add_to_payarek_woo');
	add_filter('woocommerce_currencies', 'add_eur_currencies');
	add_filter('woocommerce_currency_symbol', 'add_eur_currencies_symbol', 10, 2);
    function add_to_payarek_woo($gateways) {
        $gateways[] = 'WC_Gateway_Payarek';
        return $gateways;
	}
	function add_eur_currencies($currencies){
		$currencies['EUR'] = __('Euro', 'payarek-woo ');
		return $currencies;
	}

	function add_eur_currencies_symbol($currency_symbol, $currency){
		switch($currency) {
			case 'EUR':
				$currency_symbol = 'EUR';
			break;
		}
		return $currency_symbol;
	}
