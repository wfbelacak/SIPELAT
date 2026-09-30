<?php
class AuthMiddleware {
    
    public static function isLoggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        } else {
            return false;
        }
    }

    public static function checkRole($allowedRoles = []) {
        if(!self::isLoggedIn()){
            header('Location: ' . URLROOT . '/auth');
            exit;
        }

        if(in_array($_SESSION['user_role'], $allowedRoles)) {
            return true;
        }

        // Kalau tidak punya akses, redirect ke dashboard (atau halaman error 403)
        header('Location: ' . URLROOT . '/dashboard');
        exit;
    }
    
    public static function isAdmin(){
        return self::checkRole([1]);
    }
    
    public static function isPetugas(){
        return self::checkRole([1, 2]); // Petugas dan Admin
    }
}
