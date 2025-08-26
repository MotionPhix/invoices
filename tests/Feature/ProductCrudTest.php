<?php

use App\Models\Product;
use App\Models\Category;

describe('Product CRUD', function () {
    it('can create a product', function () {
        $product = Product::factory()->create(['name' => 'Test Product']);
        expect($product->name)->toBe('Test Product');
    });

    it('can update a product', function () {
        $product = Product::factory()->create(['name' => 'Old Name']);
        $product->update(['name' => 'New Name']);
        expect($product->fresh()->name)->toBe('New Name');
    });

    it('can delete a product', function () {
        $product = Product::factory()->create();
        $product->delete();
        expect(Product::find($product->id))->toBeNull();
    });

    it('can assign a category to a product', function () {
        $category = Category::factory()->create(['name' => 'Test Category']);
        $product = Product::factory()->create(['category_id' => $category->id]);
        expect($product->category->name)->toBe('Test Category');
    });
});
