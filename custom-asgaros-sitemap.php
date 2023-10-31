<?php
/*
Plugin Name: Custom Asgaros Sitemap
Description: Custom sitemap plugin for Asgaros Forum.
Version: 1.0
Author: Your Name
*/

function custom_asgaros_forum_sitemap($url_list) {
    // Replace this with code to fetch Asgaros Forum topic posts.
    // For simplicity, we'll use example URLs.
    $forum_posts = array(
        'https://example.com/topic1',
        'https://example.com/topic2',
        'https://example.com/topic3',
    );

    foreach ($forum_posts as $url) {
        $url_list[] = array(
            'loc' => $url,
            'lastmod' => date('c'), // Use the current date and time.
            'changefreq' => 'monthly',
            'priority' => '0.5',
        );
    }
    return $url_list;
}

add_filter('wp_sitemaps_posts_entry', 'custom_asgaros_forum_sitemap');