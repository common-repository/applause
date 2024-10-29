<?php
/*
Plugin Name: Applause
Description: Add an applause/like/upvote button to your content.
Version: 0.1
Requires at least: 5.0
Author: Bryan Hadaway
Author URI: https://calmestghost.com/
License: Public Domain
License URI: https://wikipedia.org/wiki/Public_domain
Text Domain: applause
*/

if ( !defined( 'ABSPATH' ) ) {
	http_response_code( 404 );
	die();
}

add_action( 'wp_enqueue_scripts', 'applause_enqueue' );
function applause_enqueue() {
	wp_enqueue_style( 'applause-style', plugin_dir_url( __FILE__ ) . 'applause.css' );
	wp_register_script( 'applause-script', plugin_dir_url( __FILE__ ) . 'applause.js' );
	wp_enqueue_script( 'applause-script' );
}

add_filter( 'the_content', 'applause_default_placement' );
function applause_default_placement( $content ) {
	if ( is_single() && !is_preview() ) {
		$beforecontent = do_shortcode( '[applause]' );
		$aftercontent = do_shortcode( '[applause]' );
		$fullcontent = $beforecontent . $content . $aftercontent;
	} else {
		$fullcontent = $content;
	}
	return $fullcontent;
}

add_shortcode( 'applause', 'applause_shortcode' );
function applause_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'url'   => esc_url( get_the_permalink() ),
		'color' => 'gray',
	), $atts );
	ob_start();
	echo '<div class="applause"><applause-button url="' . esc_url( $atts['url'] ) . '" color="' . esc_attr( $atts['color'] ) . '" /></div>';
	echo '<style>applause-button{width:32px;height:32px}applause-button .count-container .count{font-size:14px;color:' . esc_attr( $atts['color'] ) . ';margin:-8px}</style>';
	$output = ob_get_clean();
	return $output;
}