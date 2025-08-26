<?php

use App\Models\SupportRequest;
use App\Models\Client;

describe('Support Request', function () {
    it('can create a support request for a client', function () {
        $client = Client::factory()->create();
        $support = SupportRequest::factory()->create([
            'client_id' => $client->id,
            'subject' => 'Need help',
            'message' => 'Please assist',
            'priority' => 'high',
            'status' => 'open',
        ]);
        expect($support->client_id)->toBe($client->id);
        expect($support->subject)->toBe('Need help');
    });
});
