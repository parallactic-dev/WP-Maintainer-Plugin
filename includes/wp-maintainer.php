<?php

class WP_Maintianer_Plugin
{

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'update_checker_register_endpoint'));
    }

    // Register the REST endpoint
    public function update_checker_register_endpoint()
    {
        register_rest_route('wp/v2', '/status', array(
            'methods' => 'GET',
            'callback' => 'update_checker_check_status',
        ));
    }

    // Callback function to check the WordPress update status
    public function update_checker_check_status($request)
    {
        $items = array();

        // core
        global $wp_version;

        wp_version_check();
        $transient = get_site_transient('update_core');

        if (empty($transient->updates)) {
            $items[] = array(
                'id' => 'core',
                'slug' => 'wordpress',
                'latest' => $wp_version,
                'current' => $wp_version,
                'lastchecked' => $transient->last_checked,
            );
        } else {
            $items[] = array(
                'id' => 'wp-core',
                'slug' => 'wordpress',
                'latest' => $transient->updates[0]->version,
                'current' => $wp_version,
                'lastchecked' => $transient->last_checked,
            );
        }

        // plugins
        wp_update_plugins();
        $transient = get_site_transient('update_plugins');

        foreach ($transient->response as $key => $value) {
            $items[] = array(
                'id' => $value->id,
                'slug' => $value->slug,
                'latest' => $value->new_version,
                'current' => $transient->checked[$key],
                'lastchecked' => $transient->last_checked,
            );
        }

        return rest_ensure_response($items);
    }
}
