# My API Plugin Documentation

## Table of Contents
1. Introduction
   - About the Plugin
   - Installation
2. Plugin Settings
   - General Settings
   - API Configuration
3. Shortcodes
   - [my_api_products]
   - [my_api_categories]
4. API Endpoints
   - /my-api/v1/products
   - /my-api/v1/categories
5. Troubleshooting
   - Common Issues
   - Support

## 1. Introduction

### About the Plugin
The My API Plugin is a WordPress plugin that provides custom API functionality for retrieving product data from your website. It allows you to expose product information via RESTful API endpoints and provides various features to enhance the functionality of your website.

### Installation
To install the My API Plugin, follow these steps:

1. Download the plugin ZIP file from the official WordPress plugin repository or the provided source.
2. Log in to your WordPress admin panel.
3. Navigate to **Plugins** -> **Add New**.
4. Click on the **Upload Plugin** button at the top of the page.
5. Select the downloaded ZIP file and click **Install Now**.
6. After installation, click **Activate** to activate the plugin.

## 2. Plugin Settings

### General Settings
The My API Plugin provides some general settings that can be configured to control the behavior of the plugin. To access the settings, follow these steps:

1. Log in to your WordPress admin panel.
2. Navigate to **Settings** -> **My API Plugin**.

Here you can configure options such as enabling or disabling the plugin, setting cache expiration time, and defining API key requirements.

### API Configuration
The API configuration section allows you to customize the API endpoints and manage access permissions. You can set the base URL for the API and define which user roles have access to the API endpoints.

## 3. Shortcodes

The My API Plugin provides shortcodes that you can use to display product information and categories on your website. These shortcodes can be placed within posts, pages, or widgets to render dynamic content.

### [my_api_products]
Use the `[my_api_products]` shortcode to display a list of products on your website. This shortcode supports various attributes to customize the output, such as specifying the number of products to display, filtering by category, or sorting options.

Example usage:
```
[my_api_products limit="10" category="clothing" orderby="title" order="asc"]
```

### [my_api_categories]
The `[my_api_categories]` shortcode can be used to display a list of product categories on your website. This shortcode accepts attributes to control the display, such as showing the category count or hiding empty categories.

Example usage:
```
[my_api_categories show_count="true" hide_empty="false"]
```

## 4. API Endpoints

The My API Plugin provides the following API endpoints to retrieve product data:

### /my-api/v1/products
- Method: GET
- Description: Retrieve a list of products with their details, including title, price, description, SKU, variations, and images.
- Parameters:
  - `limit` (optional): The maximum number of products to retrieve. Default is all products.
  - `category` (optional): Filter products by category.
  - `orderby` (optional): Sort products by a specific field (e.g., `title`, `price`, `date`).
  - `order` (optional): Sort order, either `asc` (ascending) or `desc` (descending).

### /my-api/v1/categories
- Method: GET
- Description: Retrieve a list of

 product categories with their details, including name and count of associated products.
- Parameters:
  - `hide_empty` (optional): Hide empty categories. Default is false.

## 5. Troubleshooting

### Common Issues
- **Plugin not working**: Make sure the plugin is activated and configured correctly. Check the plugin settings and verify that the API endpoints are accessible.
- **Empty API responses**: Ensure that there are products and categories available on your website. If the response is still empty, check for any filters or restrictions that may affect the output.

### Support
If you encounter any issues or need assistance with the My API Plugin, you can reach out to our support team by [contacting us](mailto:aldinsmajkan2@gmail.com). Please provide detailed information about the problem you are experiencing and any relevant error messages.

---
