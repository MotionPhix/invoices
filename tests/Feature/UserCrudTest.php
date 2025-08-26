<?php

use App\Models\User;

describe('User CRUD', function () {
    it('can create a user', function () {
        $user = User::factory()->create(['name' => 'Test User']);
        expect($user->name)->toBe('Test User');
    });

    it('can update a user', function () {
        $user = User::factory()->create(['name' => 'Old Name']);
        $user->update(['name' => 'New Name']);
        expect($user->fresh()->name)->toBe('New Name');
    });

    it('can delete a user', function () {
        $user = User::factory()->create();
        $user->delete();
        expect(User::find($user->id))->toBeNull();
    });
});
