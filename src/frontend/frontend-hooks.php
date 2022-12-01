<?php
function _pwa_scripts()
{
    wp_register_script('_pwa-js', plugins_url( 'simple-pwa/assets/js/pwa.js' ), array('jquery'), null, true);
    wp_localize_script( '_pwa-js', 'pwa_params', array(
        'sw_path' => plugins_url( 'simple-pwa/sw.js' ),
        'siteurl' => site_url()
    ));
    wp_enqueue_script('_pwa-js');
}

add_action('wp_enqueue_scripts', '_pwa_scripts');

function _pwa_head()
{
    echo '<link rel="manifest" href="'.plugins_url('simple-pwa/manifest.json' ).'">';
}
add_action('wp_head', '_pwa_head');