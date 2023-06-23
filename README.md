## My API Plugin Documentation

### Description
The My API Plugin is a WordPress plugin designed to handle custom API functionality. It provides an API route to retrieve product data and includes a dashboard page to enable or disable the plugin.

### Installation
1. Download the plugin code.
2. Upload the plugin folder to the `wp-content/plugins/` directory in your WordPress installation.
3. Activate the plugin through the WordPress admin panel.

### Plugin Details
- Plugin Name: My API Plugin
- Description: A plugin to handle custom API functionality.
- Version: 1.0
- Author: Aldin Smajkan

### Enqueue Scripts and Stylesheets
The plugin enqueues a script called `my-api-script.js` using the `wp_enqueue_script` function. This script is dependent on jQuery and is loaded in the footer of the web page.

### Register Custom API Route
The plugin registers a custom API route `/my-api/v1/products` using the `register_rest_route` function. The route is accessible via the HTTP GET method and invokes the `my_api_get_products` callback function.

### Callback Function to Retrieve Product Data
The `my_api_get_products` function is the callback for the custom API route. It retrieves product data and returns a JSON response containing the product information. The function performs the following steps:

1. Checks if the plugin is enabled. If it is disabled, it returns a WP_Error object with a status code of 403.
2. Retrieves the products using the `get_posts` function with specific arguments.
3. Iterates through each product and retrieves the necessary data such as ID, title, price, short description, SKU, variations, and images.
4. Processes the data as needed (not specified in the code) and builds an array of product information.
5. Returns the array as the API response.

### Dashboard Menu
The plugin adds a menu page to the WordPress dashboard called "My API Plugin." It can be accessed by users with the "manage_options" capability. The menu item uses the dashicon `dashicons-admin-plugins` and has a position of 75.

### Callback Function to Display Dashboard Page
The `my_api_plugin_dashboard` function is the callback for the dashboard menu page. It displays the plugin's dashboard page, showing the current status of the plugin (enabled or disabled) and provides buttons to enable or disable the plugin.

### AJAX Callbacks to Enable/Disable the Plugin
The plugin uses AJAX callbacks to handle enabling and disabling of the plugin. It includes two functions: `my_api_plugin_enable` and `my_api_plugin_disable`.

1. `my_api_plugin_enable`:
   - Verifies the security nonce for authentication.
   - Updates the plugin status by setting the `my_api_plugin_enabled` option to true.

2. `my_api_plugin_disable`:
   - Verifies the security nonce for authentication.
   - Updates the plugin status by setting the `my_api_plugin_enabled` option to false.

### JavaScript File - `js/my-api-script.js`
The `my-api-script.js` file is enqueued by the plugin and executed when the DOM is ready. It performs an AJAX request to the custom API route `/wp-json/my-api/v1/products` to retrieve the product data.

Upon successful retrieval of the data, the script processes the response and creates an array of processed product information. The processed product data can be used as needed in the application.

### Note
This documentation provides an overview of the My API Plugin's code and functionality. For more detailed information, refer to the plugin's code comments and implementation details.
