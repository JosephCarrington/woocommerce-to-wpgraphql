<?php
/**
 * Plugin Name: WooCommerce to WP GraphQL
 * Description: Exposes WooCommerce products and their relevant post_meta to WP GraphQL
 * Author: Joseph Carrington
 * Author URI: https://github.com/JosephCarrington
 * Version: 0.0.1
 * License: WTFPL
 * License URI: http://www.wtfpl.net/txt/copying/


/** Add Woocommerce integration to WPGraphQL if it's installed */
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
