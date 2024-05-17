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
            'callback' => array($this, 'update_checker_check_status'),
        ));
    }

    // Callback function to check the WordPress update status
    public function update_checker_check_status()
    {
        $items = array();

        // core
        global $wp_version;

        wp_version_check();
        $transient = get_site_transient('update_core');

        if (empty($transient->updates)) {
            $items['core'] = array(
                'type' => 'core',
                'slug' => 'wordpress',
                'latest' => $wp_version,
                'current' => $wp_version,
                'lastchecked' => $transient->last_checked,
            );
        } else {
            $items['core'] = array(
                'type' => 'core',
                'slug' => 'wordpress',
                'latest' => $transient->updates[0]->version,
                'current' => $wp_version,
                'lastchecked' => $transient->last_checked,
            );
        }

        // plugins
        wp_update_plugins();
        $plugins = get_plugins();
        foreach ($plugins as $plugin_file => $plugin_data) {
            $slug = explode('/', $plugin_file)[0];
            $items['plugins'][$slug] = array(
                'type' => 'plugin',
                'slug' => $slug,
                'name' => $plugin_data['Name'],
                'title' => $plugin_data['Title'],
                'description' => $plugin_data['Description'],
                'author' => $plugin_data['Author'],
                'pluginURI' => $plugin_data['PluginURI'],
                'textdomain' => $plugin_data['TextDomain'],
                'latest' => $plugin_data['Version'],
                'current' => $plugin_data['Version'],
                'wp' => $plugin_data['RequiresWP'],
                'php' => $plugin_data['RequiresPHP'],
                'lastchecked' => null,
            );
        }
        $transient = get_site_transient('update_plugins');

        foreach ($transient->response as $key => $value) {
            $items['plugins'][$value->slug]['latest'] = $value->new_version;
            $items['plugins'][$value->slug]['lastchecked'] = $transient->last_checked;
        }

        return rest_ensure_response($items);
    }
}
