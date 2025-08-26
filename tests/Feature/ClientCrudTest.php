<?php

use App\Models\Client;
use App\Models\User;

describe('Client CRUD', function () {
    it('can create a client', function () {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id, 'name' => 'Test Client']);
        expect($client->name)->toBe('Test Client');
        expect($client->user->id)->toBe($user->id);
    });

    it('can update a client', function () {
        $client = Client::factory()->create(['name' => 'Old Name']);
        $client->update(['name' => 'New Name']);
        expect($client->fresh()->name)->toBe('New Name');
    });

    it('can soft delete a client', function () {
        $client = Client::factory()->create();
        $client->delete();
        expect(Client::withTrashed()->find($client->id))->not->toBeNull();
        expect(Client::find($client->id))->toBeNull();
    });
});
