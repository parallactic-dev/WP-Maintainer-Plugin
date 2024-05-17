# WP Maintainer Plugin

WP Maintainer is a powerful WordPress plugin designed to give you a comprehensive overview of your WordPress core and plugin statuses via the WordPress REST API. Whether you manage a single site or multiple WordPress installations, WP Maintainer simplifies the process of monitoring and maintaining your site's health by providing critical version information at your fingertips.


## Features

### REST API Status Endpoint

WP Maintainer introduces a dedicated REST endpoint that allows you to quickly check the status of your WordPress installation:

```
/wp-json/wp/v2/status
```
By accessing this endpoint, you can retrieve detailed information about the versions of your WordPress core and installed plugins. This data helps you ensure that your site is up-to-date and secure. The response includes:

- *ID*: The unique identifier for the WordPress core or plugin.
- *Slug*: The slug used to identify the plugin in the WordPress repository.
- *Latest Version*: The most recent version available.
- *Current Version*: The version currently installed on your site.
- *Last Checked*: The timestamp of the last check performed.

Here is an example response from the endpoint:

```
{
    "id": "wp-core",
    "slug": "wordpress",
    "latest": "6.5.3",
    "current": "6.5.3",
    "lastchecked": 1715933826
},
{
    "id": "w.org/plugins/disable-gutenberg",
    "slug": "disable-gutenberg",
    "latest": "3.1.1",
    "current": "2.9",
    "lastchecked": 1715933831
},
{
    "id": "w.org/plugins/loginizer",
    "slug": "loginizer",
    "latest": "1.8.4",
    "current": "1.8.1",
    "lastchecked": 1715933831
}
```

## Installation

WP Maintainer is easy to install using Composer. Follow these steps to add it to your WordPress project:

```
composer require parallactic/wp-maintainer
```
