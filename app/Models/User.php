<?php

namespace App\Models;
use App\Config\Database; // อ้างอิง Database class จาก Config

class User {
    // GET ByUID
    public static function getByUID($uid) {
        // var_dump("chek");
        $stmt = Database::connect()->prepare("SELECT * FROM users WHERE uid = ?");
        $stmt->execute([$uid]);
        return $stmt->fetch(); 
    }

    public static function createFromFirebase($request) {
        $uid = $request->getAttribute('uid');
        $email = $request->getAttribute('email');
        $stmt = Database::connect()->prepare("INSERT INTO users (uid, email) VALUES (?, ?)");
        $stmt->execute([$uid, $email]); 
        // return ['message' => 'User created successfully'];
    }
}


