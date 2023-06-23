jQuery(document).ready(function($) {
    // Make an AJAX request to retrieve the products
    $.ajax({
      url: '/wp-json/my-api/v1/products',
      method: 'GET',
      dataType: 'json',
      success: function(response) {
        // Handle the response data
        if (response.length > 0) {
          // Create an array to store the processed product data
          var products = [];
  
          // Loop through each product
          $.each(response, function(index, product) {
            // Access the product data
            var productId = product.id;
            var title = product.title;
            var price = product.price;
            var shortDescription = product.short_description;
            var sku = product.sku;
            var variations = product.variations;
            var images = product.images;
  
            // Process the product data as needed
            // ...
  
            // Create an object to store the processed product information
            var processedProduct = {
              id: productId,
              title: title,
              price: price,
              shortDescription: shortDescription,
              sku: sku,
              variations: variations,
              images: images
            };
  
            // Add the processed product to the products array
            products.push(processedProduct);
          });
  
          // Use the products array as needed in your application
          console.log('Products:', products);
        } else {
          console.log('No products found.');
        }
      },
      error: function(xhr, status, error) {
        console.log('AJAX Error:', error);
      }
    });
  });
  