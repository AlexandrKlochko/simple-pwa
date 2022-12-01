<?php
/**
 * Plugin Name: Simple PWA
 * Description: Add PWA to site
 * Version: 1.0.0
 * Text Domain: simple_pwa
 * Domain Path: /i18n/languages/
 * Requires at least: 5.2
 * Requires PHP: 7.0
 *
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/src/admin/admin-hooks.php';
require __DIR__ . '/src/admin/admin-ajax.php';
require __DIR__ . '/src/frontend/frontend-hooks.php';

register_activation_hook( __FILE__, '_activation_simple_pwa' );
function _activation_simple_pwa(){
    add_option('simple_pwa_name',get_bloginfo('name'));
    add_option('simple_pwa_short_name',get_bloginfo('name'));
    add_option('simple_pwa_display','standalone');
    add_option('simple_pwa_orientation','portrait');
    add_option('simple_pwa_background_color','');
    add_option('simple_pwa_theme_color','');
    add_option('simple_pwa_icons','');
    $script = "const siteName = '".site_url()."';
const staticCacheName = 'pwa-cache-v1';
const filesToCache = [
    '/',
];
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    );
});

self.addEventListener('activate', evt => {
    console.log('[ServiceWorker]  Activated');
    evt.waitUntil(
        caches.keys().then(function (keyList) {
            return Promise.all(keyList.map(function (key) {
                if (key !== staticCacheName) {
                    console.log('[ServiceWorker] Removing old cache', key);
                    return caches.delete(key);
                }
            }));
        })
    );
    return self.clients.claim();
});

self.addEventListener('fetch', function (event) {
    if (event.request.url.includes(siteName) &&
        event.request.method === 'GET' &&
        event.request.url.toString() &&
        event.request.url.toString().indexOf('/checkout/') === -1 &&
        event.request.url.toString().indexOf('/cart/') === -1 &&
        event.request.url.toString().indexOf('/wp-json/') === -1 &&
        event.request.url.toString().indexOf('/wp-admin/') === -1){
            event.respondWith(
                fetch(event.request).then(function (response) {
                    return caches.open(staticCacheName).then(function (cache) {
                        cache.put(event.request, response.clone());
                        return response;
                    })
                }).catch(function () {
                    return caches.match(event.request);
                })
            )
    }
});";

    $file = fopen(realpath(ABSPATH).'/sw.js' , 'w+');
    fwrite($file, $script);
    fclose($file);

}

register_uninstall_hook( __FILE__, '_uninstall_simple_pwa' );
function _uninstall_simple_pwa(){
    delete_option('simple_pwa_name');
    delete_option('simple_pwa_short_name');
    delete_option('simple_pwa_display');
    delete_option('simple_pwa_orientation');
    delete_option('simple_pwa_background_color');
    delete_option('simple_pwa_theme_color');
    delete_option('simple_pwa_icons');
    unlink(realpath(ABSPATH).'/sw.js');
}