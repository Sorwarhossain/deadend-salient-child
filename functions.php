<?php 

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
	
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );


   	wp_enqueue_script('scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array(), false, true);
}


// add_filter( 'add_to_cart_text', 'woo_custom_cart_button_text',  1, 20);    // < 2.1
// function woo_custom_cart_button_text() {
//         return __( 'Find Store', 'woocommerce' );;
// }


add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
 
function woo_custom_cart_button_text() {
 
        return __( 'Find Store Location', 'woocommerce' );
 
}



/**
 * Set a custom add to cart URL to redirect to
 * @return string
 */
function custom_add_to_cart_redirect() { 
    return get_permalink( '6412' ); 
}
add_filter( 'woocommerce_add_to_cart_redirect', 'custom_add_to_cart_redirect' );
