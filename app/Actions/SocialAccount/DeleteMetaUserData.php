<?php

declare(strict_types=1);

namespace App\Actions\SocialAccount;

use App\Enums\PostPlatform\Status as PostPlatformStatus;

class DeleteMetaUserData
{
    /**
     * Honor a Meta data deletion request: remove every account (and its stored
     * tokens) connected through the user. Pending post rows are dropped the same
     * way a manual disconnect does; published history keeps its snapshot fields.
     */
    public static function execute(string $userId): int
    {
        $accounts = FindMetaUserAccounts::execute($userId);

        foreach ($accounts as $account) {
            $account->postPlatforms()
                ->where('status', PostPlatformStatus::Pending->value)
                ->delete();

            $account->delete();
        }

        return $accounts->count();
    }
}
