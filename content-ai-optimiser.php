<?php 
/**
 * Plugin Name:  Content AI 
 * Description: A plugin to Content AI Optimiser.
 * Version: 1.0.0
 * Author: Karishma Shukla
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;

// Autoload or manually include files
require_once plugin_dir_path(__FILE__) . 'includes/class-seo-suggestions.php';

// Initialize the plugin
//add_action('init', ['SEO_Suggestions', 'init']);

add_action( 'enqueue_block_editor_assets', 'content_ai_optimizer_enqueue' );

function content_ai_optimizer_enqueue() {
    wp_enqueue_script(
        'content-ai-optimizer',
        plugins_url( 'build/index.js', __FILE__ ),
        [ 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data' ],
        filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' )
    );

    wp_enqueue_style(
        'content-ai-optimizer',
        plugins_url( 'build/index.css', __FILE__ ),
        [],
        filemtime( plugin_dir_path( __FILE__ ) . 'build/index.css' )
    );
}
