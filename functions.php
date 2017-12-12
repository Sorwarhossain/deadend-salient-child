<?php 
function deadend_child_setup() {
	add_theme_support( 'wc-product-gallery-slider' );

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
}
add_action( 'after_setup_theme', 'deadend_child_setup' );

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
	
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );

	wp_enqueue_script('jquery');
   	wp_enqueue_script('scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), false, true);
}




// set all wc products to external 
add_action('init', 'wc_product_set_to_external');
function wc_product_set_to_external(){
	$all_products = get_posts(
		array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'fields'	=> 'ids',
			'posts_per_page' => -1,
		)
	);

	$on_site_products = get_posts(
		array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'fields'	=> 'ids',
			'posts_per_page' => -1,
			'product_cat' => 'on-site-products',
		)
	);

	$external_products = array_diff($all_products, $on_site_products);

	foreach ($external_products as $id ) {
		wp_set_object_terms($id, 'external', 'product_type');
	}	

	foreach ($on_site_products as $id ) {
		wp_set_object_terms($id, 'simple', 'product_type');
	}
}

// handle external products text and link 
add_filter( 'woocommerce_product_single_add_to_cart_text', 'change_external_product_btn_text', 10, 2 );

add_filter( 'woocommerce_product_add_to_cart_url',
'change_external_products_link', 10, 2 );

function change_external_product_btn_text( $button_text, $product ) {
    if ( 'external' === $product->get_type() ) {
        // enter the default text for external products
       	$button_text = $product->button_text ? $product->button_text : 'Find Store Location';
    }
    return $button_text;
}

function change_external_products_link(  $url, $product ) {
    if ( 'external' === $product->get_type() ) {
        $url = get_permalink( '6412' ); // 6412 is the store locator page ID
    }
    return $url;
}





// add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
 
function woo_custom_cart_button_text($text) {
	global $product;
 	$id = $product->get_id();
	$product_cats = wp_get_post_terms( $id, 'product_cat' );

	$list_cat = array();
	foreach($product_cats as $cat){
		$list_cat[] = $cat->slug;
	}

 	if(!in_array('apparel-decals', $list_cat)){
 		return __( 'Find Store Location', 'woocommerce' );
 	}
 	
 	return $text;
}



/**
 * Redirect users after add to cart.
 */
function my_custom_add_to_cart_redirect( $url ) {
	if ( ! isset( $_REQUEST['add-to-cart'] ) || ! is_numeric( $_REQUEST['add-to-cart'] ) ) {
		return $url;
	}
	$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
	// Only redirect products that have the 't-shirts' category
	if ( has_term( 'apparel-decals', 'product_cat', $product_id ) ) {
		return $url;	
	} else {
		return $url = get_permalink( '6412' );
	}
	
}
// add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );









/**
 * customize login screen
 *
 */
function deadend_custom_login_page() {
    echo '<style type="text/css">
        h1 a { background-image:url("'. get_stylesheet_directory_uri().'/images/logo.png") !important; height: 120px !important; width: 100% !important; margin: 0 auto !important; background-size: contain !important; }
		h1 a:focus { outline: 0 !important; box-shadow: none; }
        body.login { background-image:url("'. get_stylesheet_directory_uri().'/images/Login-banner.jpg") !important; background-repeat: no-repeat !important; background-attachment: fixed !important; background-position: center !important; background-size: cover !important; position: relative; z-index: 999;}
  		body.login:before { background-color: rgba(0, 0, 0, 0.9); position: absolute; width: 100%; height: 100%; left: 0; top: 0; content: ""; z-index: -1; }
  		.login form {
  			background: rgba(255,255,255, 0.2) !important;
  		}
		.login form .input, .login form input[type=checkbox], .login input[type=text] {
			background: transparent !important;
			color: #ddd;
		}
		.login label {
			color: #DDD !important;
		}
		.login #login_error, .login .message {
			color: #ddd;
			margin-top: 20px;
			background: rgba(255,255,255, 0.2) !important;
		}
    </style>';
}
add_action('login_head', 'deadend_custom_login_page');





