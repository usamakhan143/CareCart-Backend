<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function getProducts()
    {
        $api_key = request('api_key');
        $category_id = request('category_id');
        $api_url = $api_url = 'https://api.redcircleapi.com/request?api_key=' . $api_key . '&type=category&category_id=' . $category_id;
        $get_posts = Http::get($api_url);
        if ($get_posts->successful()) {
            $data = $get_posts->json();

            // Access the category_results array
            $categoryResults = $data['category_results'];

            // Create an array to store customized product data
            $customProducts = [];

            // Loop through the products in the category_results array
            foreach ($categoryResults as $result) {
                $product = $result['product'];
                $pricing = $result['offers'];
                // Customize the product data as needed
                $customProduct = [
                    'title' => $product['title'],
                    'description' => $product['feature_bullets'][0],
                    'brand' => $product['brand'],
                    'price' => $pricing['primary']['price'],
                    'imageurls' => $product['images']
                    // Add other customized properties here
                ];

                // Add the customized product to the array
                $customProducts[] = $customProduct;
            }

            // Return the customized data as the response
            return response()->json($customProducts);
        } else {
            return response()->json([
                'error' => 'Failed to fetch data from the external API',
                'api_key' => $api_url
            ], 500);
        }
    }
}
