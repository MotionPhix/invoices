<?php

use App\Models\Category;

describe('Category CRUD', function () {
    it('can create a category', function () {
        $category = Category::factory()->create(['name' => 'Test Category']);
        expect($category->name)->toBe('Test Category');
    });

    it('can update a category', function () {
        $category = Category::factory()->create(['name' => 'Old Name']);
        $category->update(['name' => 'New Name']);
        expect($category->fresh()->name)->toBe('New Name');
    });

    it('can delete a category', function () {
        $category = Category::factory()->create();
        $category->delete();
        expect(Category::find($category->id))->toBeNull();
    });
});
