<?php

//ini_set('display_startup_errors', 1);
//ini_set('display_errors', 1);
//error_reporting(-1);


if(!defined('ABSPATH'))
    define('ABSPATH', __DIR__ . '/../../');
require_once ABSPATH . '/wp-load.php';

$langs = [
    'en',
    'fr',
    'es',
];

global $sitepress;

foreach ($langs as $lang => $status){
    $sitepress->switch_lang($lang);

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => array('draft')
    );
    $query = new WP_Query( $args );


    while ($query->have_posts()) : $query->the_post();

        $post_id = get_the_ID();

        $product = wc_get_product($post_id);
        $product_image_url = wp_get_attachment_url($product->get_image_id());

        if(!empty($product_image_url) && basename($product_image_url) != 'soon.png' && $product->is_in_stock()){
//            echo $post_id . ' -- ' . wp_get_attachment_url($product->get_image_id()) . '<br/>';

            wp_update_post([
                'ID' =>  $post_id,
                'post_status' => 'publish'
            ]);
        }


    endwhile;
}


echo 'Done';