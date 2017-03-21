<?php

namespace Czech\Auth;

use Czech\Models\Student as DB;

class Student {

    public function login($user) {
        $_SESSION['user'] = $user->id;
    }

    public function logout() {
        unset($_SESSION['user']);
    }

    public function getState() {
        return isset($_SESSION['user']);
    }

    public function getData($usr = null) {
        if (!$usr) {
            return DB::find($_SESSION['user']);
        }
        return DB::find($usr);
    }

    public function getRank() {
        return DB::find($_SESSION['user'])->is_admin;
    }

}