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
	
	add_action('plugins_loaded', 'payarek_payment_init', 11);
	
	function payarek_payment_init() {
			if(class_exists('WC_PAYMENT_GATEWAY')) {
				require_once plugin_dir_path(__FILE__) . '/includes/class-wc-payment-gateway-payarek.php';
				require_once plugin_dir_path(__FILE__) . '/includes/payarek-order-statuses.php';

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
		$currencies['EUR'] = __('Euro', 'ocotw');
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
