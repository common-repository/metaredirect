<?php
 /*
 Plugin Name: MetaRedirect
 Plugin URI: https://plugins.codecide.net/plugin/metaredirect
 Description: A plugin that automatically redirects posts to a url stored in a custom field, with optional redirection types, triggers and query string parameters.
 Version: 1.0
 Author: Codecide.net
 Author URI: http://codecide.net/
 License: GPL2
 Requirements: PHP <= 5.4, jQuery
 References:
 https://www.smashingmagazine.com/2016/04/three-approaches-to-adding-configurable-fields-to-your-plugin/
 http://www.webopius.com/content/137/using-custom-url-parameters-in-wordpress
 */
defined( 'ABSPATH' ) or exit;

define('metaredirect_pluginpath', dirname(__FILE__).'/');
define('metaredirect_pluginname', 'metaredirect');
require_once(metaredirect_pluginpath.metaredirect_pluginname.'.class.php');

add_action( 'template_redirect', metaredirect_pluginname );

function metaredirect() {
    global $post, $wp_query; 
    if (!is_singular() || !(get_option('metaredirect_enabled'))) { return; }
    $destination = esc_url(get_post_meta( $post->ID, get_option('metaredirect_customfield'), true ));
    if (!$destination || !wp_http_validate_url($destination)) { return; }
    $hasTrigger = (get_option('metaredirect_trigger')[0] === 'provisional') && true;
    $hasArgument = get_option('metaredirect_trigger_argument') && true;
    $argument = get_option('metaredirect_trigger_argument');
    if ($hasTrigger && $hasArgument) {
        metaredirect_redirect(isset($wp_query->query_vars[$argument]), $destination);
    } else if (!$hasTrigger) {
        metaredirect_redirect(true, $destination);        
    } else {
        return;
    }
}

function metaredirect_redirect($switch, $destination=null) {
    if ($query_attachment = get_option('metaredirect_query_attachment')) {
        $args = metaredirect_parse_querystring($query_attachment);
        $destination = add_query_arg($args, $destination);
    }
    if ($switch) {
        $type = get_option('metaredirect_type')[0] ? get_option('metaredirect_type')[0] : '302';
        wp_redirect($destination, $type);
    }
    return;
}

function metaredirect_parse_querystring($qs) {
    parse_str($qs, $args);
    return $args;    
}

new metaredirect();