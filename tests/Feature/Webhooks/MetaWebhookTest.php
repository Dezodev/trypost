<?php

declare(strict_types=1);

use App\Enums\SocialAccount\Platform;
use App\Enums\SocialAccount\Status;
use App\Models\SocialAccount;
use App\Models\Workspace;
use App\Services\Social\Meta\SignedRequest;

function metaSignedRequest(array $payload, string $secret): string
{
    $encode = fn (string $value): string => rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    $encodedPayload = $encode(json_encode(['algorithm' => 'HMAC-SHA256', ...$payload]));

    return $encode(hash_hmac('sha256', $encodedPayload, $secret, true)).'.'.$encodedPayload;
}

beforeEach(function () {
    config([
        'services.facebook.client_secret' => 'facebook-secret',
        'services.instagram.client_secret' => 'instagram-secret',
        'services.threads.client_secret' => 'threads-secret',
    ]);

    $this->workspace = Workspace::factory()->create();
});

it('parses a request signed by any configured Meta app secret', function () {
    expect(SignedRequest::parse(metaSignedRequest(['user_id' => '42'], 'instagram-secret')))
        ->toMatchArray(['user_id' => '42'])
        ->and(SignedRequest::parse(metaSignedRequest(['user_id' => '42'], 'wrong-secret')))->toBeNull()
        ->and(SignedRequest::parse('not-a-signed-request'))->toBeNull();
});

it('disconnects the accounts of a user who deauthorizes the app', function () {
    $instagram = SocialAccount::factory()->create([
        'workspace_id' => $this->workspace->id,
        'platform' => Platform::Instagram,
        'platform_user_id' => '42',
    ]);
    $facebook = SocialAccount::factory()->create([
        'workspace_id' => $this->workspace->id,
        'platform' => Platform::Facebook,
        'platform_user_id' => 'page-1',
        'meta' => ['user_id' => '42'],
    ]);
    $other = SocialAccount::factory()->create([
        'workspace_id' => $this->workspace->id,
        'platform' => Platform::Threads,
        'platform_user_id' => '99',
    ]);

    $this->post(route('meta.deauthorize'), [
        'signed_request' => metaSignedRequest(['user_id' => '42'], 'facebook-secret'),
    ])->assertNoContent();

    expect($instagram->fresh()->status)->toBe(Status::Disconnected)
        ->and($facebook->fresh()->status)->toBe(Status::Disconnected)
        ->and($other->fresh()->status)->toBe(Status::Connected);
});

it('rejects a deauthorize request with an invalid signature', function () {
    $this->post(route('meta.deauthorize'), [
        'signed_request' => metaSignedRequest(['user_id' => '42'], 'wrong-secret'),
    ])->assertBadRequest();
});

it('deletes the accounts of a user and returns a confirmation code with a status url', function () {
    $account = SocialAccount::factory()->create([
        'workspace_id' => $this->workspace->id,
        'platform' => Platform::InstagramFacebook,
        'platform_user_id' => 'ig-1',
        'meta' => ['user_id' => '42'],
    ]);
    $other = SocialAccount::factory()->create([
        'workspace_id' => $this->workspace->id,
        'platform' => Platform::Instagram,
        'platform_user_id' => '99',
    ]);

    $response = $this->post(route('meta.data-deletion'), [
        'signed_request' => metaSignedRequest(['user_id' => '42'], 'facebook-secret'),
    ])->assertOk()->assertJsonStructure(['url', 'confirmation_code']);

    $this->assertModelMissing($account);
    $this->assertModelExists($other);

    $this->get($response->json('url'))->assertOk();
});

it('returns 404 for an unknown data deletion confirmation code', function () {
    $this->get(route('legal.data-deletion.status', 'unknown-code'))->assertNotFound();
});
