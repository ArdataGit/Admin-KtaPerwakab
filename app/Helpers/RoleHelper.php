<?php

namespace App\Helpers;

class RoleHelper
{
    /**
     * Cek apakah user punya akses ke menu tertentu
     */
    public static function canAccess($menuType)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        $role = $user->role;

        // Superadmin bisa akses semua
        if ($role === 'superadmin') {
            return true;
        }

        // Definisi akses per role
        $access = [
            'bendahara' => ['dashboard', 'iuran', 'donasi'],
            'pengurus' => [
                'dashboard', 
                'news', 
                'publikasi', 
                'info-duka', 
                'umkm', 
                'umkm-product',
                'bisnis',
                'home-banner',
                'struktur-organisasi'
            ],
            'admin' => ['all'], // Admin bisa akses semua kecuali management user superadmin
        ];

        // Jika role tidak terdaftar, return false
        if (!isset($access[$role])) {
            return false;
        }

        // Jika role punya akses 'all'
        if (in_array('all', $access[$role])) {
            return true;
        }

        // Cek apakah menu type ada dalam daftar akses role
        return in_array($menuType, $access[$role]);
    }

    /**
     * Get allowed roles for specific menu
     */
    public static function getAllowedRoles($menuType)
    {
        $roleAccess = [
            'dashboard' => ['superadmin', 'admin', 'bendahara', 'pengurus'],
            'iuran' => ['superadmin', 'admin', 'bendahara'],
            'donasi' => ['superadmin', 'admin', 'bendahara'],
            'user-management' => ['superadmin', 'admin'],
            'user-anggota' => ['superadmin', 'admin'],
            'news' => ['superadmin', 'admin', 'pengurus'],
            'publikasi' => ['superadmin', 'admin', 'pengurus'],
            'info-duka' => ['superadmin', 'admin', 'pengurus'],
            'umkm' => ['superadmin', 'admin', 'pengurus'],
            'umkm-product' => ['superadmin', 'admin', 'pengurus'],
            'bisnis' => ['superadmin', 'admin', 'pengurus'],
            'home-banner' => ['superadmin', 'admin', 'pengurus'],
            'struktur-organisasi' => ['superadmin', 'admin', 'pengurus'],
            'point' => ['superadmin', 'admin'],
            'division' => ['superadmin', 'admin'],
            'position' => ['superadmin', 'admin'],
        ];

        return $roleAccess[$menuType] ?? ['superadmin'];
    }
}
