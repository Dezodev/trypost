<?php

declare(strict_types=1);

namespace App\Actions\SocialAccount;

use App\Enums\SocialAccount\Platform;
use App\Models\SocialAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FindMetaUserAccounts
{
    /**
     * Accounts connected through the given Meta app-scoped user id.
     *
     * Instagram and Threads store that id as `platform_user_id`. Facebook Pages
     * and Instagram-via-Facebook are keyed by page / IG id, so the user id lives
     * in `meta.user_id`.
     *
     * @return Collection<int, SocialAccount>
     */
    public static function execute(string $userId): Collection
    {
        return SocialAccount::query()
            ->where(function (Builder $query) use ($userId): void {
                $query->where(function (Builder $direct) use ($userId): void {
                    $direct->whereIn('platform', [Platform::Instagram->value, Platform::Threads->value])
                        ->where('platform_user_id', $userId);
                })->orWhere(function (Builder $viaFacebook) use ($userId): void {
                    $viaFacebook->whereIn('platform', [Platform::Facebook->value, Platform::InstagramFacebook->value])
                        ->where('meta->user_id', $userId);
                });
            })
            ->get();
    }
}
