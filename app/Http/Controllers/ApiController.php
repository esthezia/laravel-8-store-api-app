<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ApiController extends Controller
{
    public function getProducts (Request $request, Product $product = null) {
        // $products = Product::with(['category' => function ($query) {
        //     $query->select('id', 'name as category_name');
        // }]);
        $products = Product::with('category');

        if (!empty($product)) {
            $products->find($product->id);
        } else {
            $products->orderBy('name', 'asc');
        }

        $products = $products->get(['id', 'id_category', 'name', 'sku', 'price', 'quantity', 'created_at', 'updated_at'])->toArray();

        // this is optional.
        // I just want to return only, and directly, the category name,
        // not the category json.
        foreach ($products as $i => $item) {
            $products[$i]['category_name'] = $item['category']['name'];
            unset($products[$i]['category']);
        }

        return response()->json($products);
    }

    public function getCategories (Request $request) {
        $categories = Category::orderBy('name', 'asc')->get()->all();

        return response()->json($categories);
    }

    /**
     * Get the total value of all products in store.
     *
     * NOTE: I assume the total value returned
     * should not take into consideration the quantity of each product,
     * in which case we would multiply the price of each one with its quantity.
     * Normally, I would clarify this with the one who requested the task,
     * but for the sake of the task and the speed of delivering its result,
     * I made, what I think it is, a reasonable assumption.
     */
    public function getTotalValue (Request $request) {
        $productPrices = Product::pluck('price')->toArray();

        // if we want/need precision,
        // we can use the BC Math functions, like below.
        // but we don't need it with such small numbers.
        // $sum = 0;

        // foreach ($productPrices as $price) {
        //     $sum = bcadd((string) $sum, (string) $price, 2);
        // }

        // $sum = (float) $sum;

        return response()->json(['result' => round(array_sum($productPrices), 2)]);
    }

    /**
     * Create or update a product
     */
    public function createProduct (Request $request, Product $product = null) {
        $data = $request->only([
            'category_name',
            'name',
            'sku',
            'price',
            'quantity'
        ]);

        $token = $request->header('AuthToken');

        // a bit of sanitization at first
        $data['quantity'] = isset($data['quantity']) ? (int) $data['quantity'] : 0;
        $data['price'] = isset($data['price']) ? (float) $data['price'] : 0;
        $data['category_name'] = isset($data['category_name']) ? trim($data['category_name']) : null;
        $data['name'] = isset($data['name']) ? trim($data['name']) : null;
        $data['sku'] = isset($data['sku']) ? trim($data['sku']) : null;

        // check for required fields for new product creation
        if (
            empty($product) &&
            (
                empty($token) ||
                empty($data['category_name']) ||
                empty($data['name']) ||
                empty($data['sku']) ||
                empty($data['price']) ||
                empty($data['quantity'])
            )
        ) {
            return response()->json(['error' => 'Missing required data.']);
        }

        if (empty($product)) {
            $product = new Product;
        } else {
            $isUpdate = true;
        }

        if (!empty($data['sku'])) {
            // an SKU is an alpha-numeric string of exactly 8 characters
            if (preg_match('/^[a-z0-9]{8}$/i', $data['sku']) !== 1) {
                return response()->json(['error' => 'Invalid SKU.']);
            }

            $product->sku = strtoupper($data['sku']);
        }

        if (!empty($data['category_name'])) {
            // search for the category, and if not found, create it
            $category = Category::firstWhere('name', $data['category_name']);

            if (empty($category)) {
                $category = new Category;
                $category->name = $data['category_name'];

                if (!$category->save()) {
                    return response()->json(['error' => 'The product could not be created.']);
                }
            }

            $product->id_category = $category->id;
        }

        if (!empty($data['name'])) {
            $product->name = $data['name'];
        }

        if (!empty($data['price'])) {
            $product->price = $data['price'];
        }

        if (!empty($data['quantity'])) {
            $product->quantity = $data['quantity'];
        }

        $requester = User::firstWhere('token', $token);

        if (empty($isUpdate)) {
            $product->created_by = $requester->id;
        } else {
            $product->updated_by = $requester->id;
            $product->updated_at = now();
        }

        if (!$product->save()) {
            return response()->json(['error' => 'The product could not be created.']);
        }

        $logMessage = 'User (' . $requester->id . ', ' . htmlspecialchars($requester->name) . ', ' . htmlspecialchars($requester->email) . ') ';
        $logMessage .= empty($isUpdate) ? 'created' : 'updated';
        $logMessage .= ' the product ID ' . $product->id . '.';
        // note that the timestamp at which this happened is included by default in the Laravel log file.

        Log::channel('product-updates')->info($logMessage);

        $response = ['id' => $product->id];

        if (!empty($isUpdate)) {
            $response = ['result' => true];
        }

        return response()->json($response);
    }

    public function deleteProduct (Request $request, Product $product) {
        if ($product->delete() !== true) {
            return response()->json(['error' => 'The product could not be deleted.']);
        }

        $token = $request->header('AuthToken');
        $requester = User::firstWhere('token', $token);

        $logMessage = 'User (' . $requester->id . ', ' . htmlspecialchars($requester->name) . ', ' . htmlspecialchars($requester->email) . ') deleted the product ID ' . $product->id . '.';
        // note that the timestamp at which this happened is included by default in the Laravel log file.

        Log::channel('product-updates')->info($logMessage);

        return response()->json(['result' => true]);
    }
}
