<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function testGetProducts () {
        $response = $this->get('/get-products');

        $response->assertStatus(200)
                 ->assertJsonStructure();
    }

    public function testGetProduct () {
        // create the category this product will belong to
        Category::factory()->create();

        // create the user that will be the creator of this product
        $user = User::factory()->create();

        // create the product
        $product = Product::factory()->create();

        $response = $this->get('/get-products/' . $product->id);

        $response->assertStatus(200)
                 ->assertJsonStructure()
                 ->assertJsonCount(1)
                 ->assertJsonFragment([
                    'id' => $product->id
                 ]);
    }

    public function testGetTotalValue () {
        // create the category this product will belong to
        Category::factory()->create();

        // create the user that will be the creator of this product
        $user = User::factory()->create();

        // create the product
        $products = Product::factory(2)->create();

        $response = $this->get('/get-total-value');

        $response->assertStatus(200)
                 ->assertExactJson([
                    'result' => round($products[0]->price + $products[1]->price, 2)
                 ]);
    }

    public function testCreateProduct () {
        // create the user that will be the creator of this product
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'AuthToken' => $user->token
        ])->post('/create-product', [
            'category_name' => 'Category Test',
            'name' => 'Product Test',
            'sku' => '8M7Y8OX5',
            'price' => 5,
            'quantity' => 1
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure()
                 ->assertJsonCount(1);

        $this->assertDatabaseHas('products', [
            'name' => 'Product Test',
            'sku' => '8M7Y8OX5',
            'price' => 5,
            'quantity' => 1,
            'created_by' => $user->id
        ]);
    }

    public function testCreateProductUnauthenticated () {
        // create the user that will be the creator of this product
        $user = User::factory()->create();

        // note the custom auth header is missing
        $response = $this->post('/create-product', [
            'category_name' => 'Category Test',
            'name' => 'Product Test',
            'sku' => '8M7Y8OX5',
            'price' => 5,
            'quantity' => 1
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['error']);

        $this->assertDatabaseMissing('products', [
            'name' => 'Product Test',
            'sku' => '8M7Y8OX5',
            'price' => 5,
            'quantity' => 1,
            'created_by' => $user->id
        ]);
    }

    public function testUpdateProduct () {
        // create the category this product will belong to
        Category::factory()->create();

        // create the user that will be the creator of this product
        $user = User::factory()->create();
        $userUpdater = User::factory()->create();

        // create the product
        $product = Product::factory()->create();

        $newProductData = [
            'name' => $product->name . ' EDIT',
            'sku' => str_shuffle($product->sku),
            'price' => $product->price + 1,
            'quantity' => $product->quantity + 2
        ];

        $response = $this->withHeaders([
            'AuthToken' => $userUpdater->token
        ])->patch('/create-product/' . $product->id, $newProductData);

        $response->assertStatus(200)
                 ->assertExactJson([
                    'result' => true
                 ]);

        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseHas('products', array_merge($newProductData, [
            'updated_by' => $userUpdater->id
        ]));
    }

    public function testUpdateProductUnauthenticated () {
        // create the category this product will belong to
        Category::factory()->create();

        // create the user that will be the creator of this product
        $user = User::factory()->create();

        // create the product
        $product = Product::factory()->create();

        $newProductData = [
            'name' => $product->name . ' EDIT',
            'sku' => str_shuffle($product->sku),
            'price' => $product->price + 1,
            'quantity' => $product->quantity + 2
        ];

        // note the custom auth header is missing
        $response = $this->patch('/create-product/' . $product->id, $newProductData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['error']);

        $this->assertDatabaseHas('products', $product->toArray());
        $this->assertDatabaseMissing('products', $newProductData);
    }

    public function testDeleteProduct () {
        // create the category this product will belong to
        Category::factory()->create();

        // create the user that will be the creator of this product
        $user = User::factory()->create();

        // create the product
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'AuthToken' => $user->token
        ])->delete('/delete-product/' . $product->id);

        $response->assertStatus(200)
                 ->assertExactJson([
                    'result' => true
                 ]);

        $this->assertModelMissing($product);
    }

    public function testDeleteProductUnauthenticated () {
        // create the category this product will belong to
        Category::factory()->create();

        // create the user that will be the creator of this product
        $user = User::factory()->create();

        // create the product
        $product = Product::factory()->create();

        // note the custom auth header is missing
        $response = $this->delete('/delete-product/' . $product->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['error']);

        $this->assertModelExists($product);
    }
}
