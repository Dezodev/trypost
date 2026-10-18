<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Actions\SocialAccount\DeauthorizeMetaUser;
use App\Actions\SocialAccount\DeleteMetaUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Webhooks\MetaSignedRequest;
use App\Services\Social\Meta\SignedRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class MetaWebhookController extends Controller
{
    public function deauthorize(MetaSignedRequest $request): Response
    {
        DeauthorizeMetaUser::execute($this->userId($request));

        return response()->noContent();
    }

    public function dataDeletion(MetaSignedRequest $request): JsonResponse
    {
        DeleteMetaUserData::execute($this->userId($request));

        $code = (string) Str::uuid();

        Cache::put("meta_data_deletion:{$code}", true, now()->addDays(90));

        return response()->json([
            'url' => route('legal.data-deletion.status', $code),
            'confirmation_code' => $code,
        ]);
    }

    public function dataDeletionStatus(string $code): InertiaResponse
    {
        abort_unless(Cache::has("meta_data_deletion:{$code}"), SymfonyResponse::HTTP_NOT_FOUND);

        return Inertia::render('legal/DataDeletionStatus', [
            'confirmationCode' => $code,
        ]);
    }

    private function userId(MetaSignedRequest $request): string
    {
        $payload = SignedRequest::parse((string) $request->validated('signed_request'));
        $userId = (string) data_get($payload, 'user_id');

        abort_if($userId === '', SymfonyResponse::HTTP_BAD_REQUEST);

        return $userId;
    }
}
