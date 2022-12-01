<?php
function _enqueue_admin_sripts() {
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );

    wp_register_script( 'simple-pwa-admin-scripts', plugins_url('simple-pwa/assets/js/admin_scripts.js'), array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'simple-pwa-admin-scripts' );
}
add_action( 'admin_enqueue_scripts', '_enqueue_admin_sripts' );

add_action('admin_menu', '_plugin_menu_simple_pwa');
function _plugin_menu_simple_pwa() {
    add_menu_page('Simple PWA Options', 'Simple PWA', 'edit_plugins', 'simple-pwa-options', '_plugin_page_simple_pwa');
}

function _plugin_page_simple_pwa(){
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
    }
    $pwa_options = array(
        'name' => get_option('simple_pwa_name'),
        'short_name' => get_option('simple_pwa_short_name'),
        'display' => get_option('simple_pwa_display'),
        'orientation' => get_option('simple_pwa_orientation'),
        'background_color' => get_option('simple_pwa_background_color'),
        'theme_color' => get_option('simple_pwa_theme_color'),
        'icons' => get_option('simple_pwa_icons'),
    );
    $availableDisplayOptions = array('fullscreen','standalone','minimal-ui','browser');
    $availableOrientationOptions = array('any','natural','landscape','landscape-primary','landscape-secondary','portrait','portrait-primary','portrait-secondary');
    
    set_query_var('availableDisplayOptions', $availableDisplayOptions);
    set_query_var('availableOrientationOptions', $availableOrientationOptions);
    set_query_var('pwa_options ', $pwa_options );
    require_once WP_PLUGIN_DIR . '/simple-pwa/templates/admin/admin-options.php';
}