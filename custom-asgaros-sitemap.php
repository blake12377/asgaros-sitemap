<?php
/*
Plugin Name: Custom Asgaros Forum Topic Sitemap
Description: Custom sitemap plugin for Asgaros Forum topics.
Version: 1.0.1
Author: Blake Whitford
*/

// Function to get approved Asgaros Forum topics
function get_asgaros_forum_topics() {
    global $wpdb; // WordPress database object

    // Replace 'wpum_forum_topics' with the actual table name used by Asgaros Forum.
    $table_name = $wpdb->prefix . 'forum_topics';

    // Modify the SQL query to select only approved topics.
    $query = "SELECT slug FROM $table_name WHERE approved = 1";

    $results = $wpdb->get_results($query);

    $topic_slugs = array();
    foreach ($results as $result) {
        $topic_slugs[] = $result->slug;
    }

    return $topic_slugs;
}

// Function to create the sitemap XML with only lastmod
function create_asgaros_sitemap() {
    header('Content-type: application/xml; charset=utf-8');

    $forum_topics = get_asgaros_forum_topics();

    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($forum_topics as $slug) {
        $url = rtrim(get_site_url(), '/') . '/forum/topic/' . $slug;

        echo '<url>';
        echo '<loc>' . esc_url($url) . '</loc>';
        echo '<lastmod>' . date('c') . '</lastmod>';
        echo '</url>';
    }

    echo '</urlset>';
}

// Hook to create the sitemap page
function add_asgaros_sitemap_page() {
    add_rewrite_rule('test-forum-topics\.xml$', 'index.php?asgaros_sitemap=1', 'top');
    add_filter('query_vars', function ($query_vars) {
        $query_vars[] = 'asgaros_sitemap';
        return $query_vars;
    });

    add_action('template_redirect', function () {
        if (get_query_var('asgaros_sitemap')) {
            create_asgaros_sitemap();
            exit;
        }
    });
}

add_action('init', 'add_asgaros_sitemap_page');

// Function to create a dashboard section
function custom_asgaros_sitemap_dashboard_section() {
    add_submenu_page(
        'tools.php',
        'Asgaros Sitemap',
        'Asgaros Sitemap',
        'manage_options',
        'asgaros-sitemap', // Change the menu slug if needed
        'custom_asgaros_sitemap_page'
    );
}

add_action('admin_menu', 'custom_asgaros_sitemap_dashboard_section');

// Function to display dashboard page content
function custom_asgaros_sitemap_page() {
    $sitemap_url = get_site_url() . '/test-forum-topics.xml';
    $forum_topics = get_asgaros_forum_topics();
    ?>
    <div class="wrap">
        <h2>Asgaros Sitemap</h2>
        <p>Live Sitemap Link: <a href="<?php echo esc_url($sitemap_url); ?>" target="_blank"><?php echo esc_html($sitemap_url); ?></a></p>
        <h3>Forum Topic URLs:</h3>
        <ul>
            <?php
            $site_url = get_site_url();
            foreach ($forum_topics as $slug) {
                $url = rtrim($site_url, '/') . '/forum/topic/' . $slug;
                echo '<li><a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a></li>';
            }
            ?>
        </ul>
    </div>
    <?php
}
