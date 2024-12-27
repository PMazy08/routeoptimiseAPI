<?php

namespace App\Helpers;

use App\Models\User;

class UserHelper
{
    public static function getUserFromRequest($request)
    {
        $uid = $request->getAttribute('uid');
        $user = User::getByUID($uid);
        if (!$user) {
            throw new \Exception('User not found');
        }
        return $user;
    }
}