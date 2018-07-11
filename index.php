<?php
/**
 * Plugin Name: WooCommerce to WP GraphQL
 * Description: Exposes WooCommerce products and their relevant post_meta to WP GraphQL
 * Author: Joseph Carrington
 * Author URI: https://github.com/JosephCarrington
 * Version: 0.0.2
 * Text Domain: woocommerce-to-wpgraphql
 * License: WTFPL
 * License URI: http://www.wtfpl.net/txt/copying/
 **/


// Add Woocommerce integration to WPGraphQL if it's installed

use WPGraphQL\Types;

add_filter('register_post_type_args', function($args, $post_type_name) {
	switch($post_type_name) {
	case 'product':
		$args['show_in_graphql'] = true;
		$args['graphql_single_name'] = 'product';
		$args['graphql_plural_name'] = 'products';
	break;
	}
	return $args;
}, 10, 2);

// Add some post meta for the products to WPGraphQL
// Users https://docs.woocommerce.com/wp-content/images/wc-apidocs/class-WC_Product.html
add_filter('graphql_product_fields', function($fields) {
	$fields['price'] = [
		'type' => \WPGraphQL\Types::string(),
		'description' => __('The price of the product', 'woocommerce-to-wpgraphql'),
		'resolve' => function(\WP_Post $post) {
			$product = new WC_Product($post->ID);
			return $product->get_price();
		}
	];

	$fields['thumbnail'] = [
		'type' => \WPGraphQL\Types::string(),
		'description' => __('The product thumbnail image', 'woocommerce-to-graphql'),
		'resolve' => function(\WP_Post $post) {
			$product = new WC_Product($post->ID);
			$image_id = $product->get_image_id();
			return wp_get_attachment_image_src($image_id)[0];
		}
	];

	$fields['description'] = [
		'type' => \WPGraphQL\Types::string(),
		'description' => __('The product description', 'woocommerce-to-graphql'),
		'resolve' => function(\WP_Post $post) {
			$product = new WC_Product($post->ID);
			return $product->get_description();
		}
	];

	$fields['is_on_sale'] = [
		'type' => \WPGraphQL\Types::boolean(),
		'description' => __('Is the product currently on sale?', 'woocommerce-to-wpgraphql'),
		'resolve' => function(\WP_Post $post) {
			$product = new WC_Product($post->ID);
			return $product->is_on_sale();
		}
	];

	$fields['sale_price'] = [
		'type' => \WPGraphQL\Types::string(),
		'description' => __('The product\'s on sale price', 'woocommerce-to-wpgraphql'),
		'resolve' => function(\WP_Post $post) {
			$product = new WC_Product($post->ID);
			return $product->get_sale_price();
		}
	];

	$fields['categories'] = [
		'type' => \WPGraphQL\Types::list_of(\WPGraphQL\Types::taxonomy()),
		'description' => __('The product\'s categories', 'woocommerce-to-wpgraphql'),
		'resolve' => function(\WP_Post $post) {
			$product = new WC_Product($post->ID);
			$cat_ids = $product->get_category_ids();
			$cats = array_map(function($cat_id) {
				return get_term($cat_id);
			}, $cat_ids);
			return $cats;
		}
	];
/*
	$fields['media'] = [
		'type' => \WPGraphQL\Types::list_of(\WPGraphQL\Types:media_item()),
		'description' => __('Media items attched to the product', 'woocommerce-to-wpgraphql'),
		'resolve' => function(\WP_Post $post) {

		}
	];
*/

	return $fields;
});
