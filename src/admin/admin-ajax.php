<?php
if( wp_doing_ajax() ) {
    add_action('wp_ajax_save_simple_pwa_options', '_save_simple_pwa_options');
    add_action('wp_ajax_nopriv_save_simple_pwa_options', '_save_simple_pwa_options');
    function _save_simple_pwa_options(){
        update_option('simple_pwa_name',$_POST['pwa_name']);
        update_option('simple_pwa_short_name',$_POST['pwa_short_name']);
        update_option('simple_pwa_display',$_POST['pwa_display']);
        update_option('simple_pwa_orientation',$_POST['pwa_orientation']);
        update_option('simple_pwa_background_color',$_POST['pwa_background_color']);
        update_option('simple_pwa_theme_color',$_POST['pwa_theme_color']);
        update_option('simple_pwa_icons',$_POST['pwa_icons']);
        $manifestArray = array(
            'name'=>$_POST['pwa_name'],
            'short_name'=>$_POST['pwa_short_name'],
            'display'=>$_POST['pwa_display'],
            'orientation'=>$_POST['pwa_orientation'],
            'background_color'=>$_POST['pwa_background_color'],
            'theme_color'=>$_POST['pwa_theme_color'],
            "scope"=> "/",
            "start_url"=> "/",
            "icons" => array(array(
                "src"=> wp_get_attachment_url($_POST['pwa_icons']),
                "sizes"=> "150x150",
                "type"=> "image/png"
            )),
            "serviceworker" => array(array(
                "src" => "/sw.js",
                "scope" => "/",
                "type" => "",
                "update_via_cache" => "none"
            ))
        );

        $file = fopen(WP_PLUGIN_DIR."/simple-pwa/manifest.json", 'w+');
        fwrite($file, stripslashes(json_encode($manifestArray)));
        fclose($file);
        wp_die();
    }
}