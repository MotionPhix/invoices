<?php

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Client;

describe('Product Price', function () {
    it('can create a product price for a client', function () {
        $product = Product::factory()->create();
        $client = Client::factory()->create();
        $price = ProductPrice::factory()->create([
            'product_id' => $product->id,
            'client_id' => $client->id,
            'price' => 123.45,
            'currency' => 'USD',
        ]);
        expect($price->product_id)->toBe($product->id);
        expect($price->client_id)->toBe($client->id);
        expect((float) $price->price)->toBe(123.45);
    });
});
