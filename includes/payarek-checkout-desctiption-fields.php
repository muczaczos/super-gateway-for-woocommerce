<?php

add_filter( 'woocommerce_gateway_description', 'payarek_description_fields', 20, 2 );

function payarek_description_fields( $description, $payment_id ) {

    if ( 'payarek' !== $payment_id ) {
        return $description;
    }
    
    ob_start();

    echo '<div style="display: block; width:300px; height:auto;">';
    echo '<img src="' . plugins_url('../img/dino.jpg', __FILE__ ) . '">';
    

    woocommerce_form_field(
        'payment_number',
        array(
            'type' => 'text',
            'label' =>__( 'Payment Phone Number', 'payarek-woo' ),
            'class' => array( 'form-row', 'form-row-wide' ),
            'required' => true,
        )
    );

    woocommerce_form_field(
        'paying_network',
        array(
            'type' => 'select',
            'label' => __( 'Payment Network', 'payarek-woo' ),
            'class' => array( 'form-row', 'form-row-wide' ),
            'required' => true,
            'options' => array(
                'none' => __( 'Select Phone Network', 'payarek-woo' ),
                'mtn_mobile' => __( 'MTN Mobile Money', 'payarek-woo' ),
                'airtel_money' => __( 'Airtel Money', 'payarek-woo' ),
            ),
        )
    );

    echo '</div>';

    $description .= ob_get_clean();

    return $description;
}