<?php

declare(strict_types=1);

namespace App\Actions\SocialAccount;

class DeauthorizeMetaUser
{
    /**
     * The user removed the app from their Meta settings: mark every account
     * connected through them as disconnected so they can reconnect later.
     */
    public static function execute(string $userId): int
    {
        $accounts = FindMetaUserAccounts::execute($userId);

        foreach ($accounts as $account) {
            $account->markAsDisconnected(__('accounts.meta.deauthorized'));
        }

        return $accounts->count();
    }
}
