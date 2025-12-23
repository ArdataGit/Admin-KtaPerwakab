<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@mail.com',
                'phone' => '081111111111',
                'gender' => 'L',
                'birth_date' => '1990-01-01',
                'address' => 'Alamat Super Admin',
                'city' => 'Jakarta',
                'occupation' => 'Founder',
                'join_date' => now(),
                'profile_photo' => null,
                'status' => 'aktif',
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'phone' => '081222222222',
                'gender' => 'L',
                'birth_date' => '1992-02-02',
                'address' => 'Alamat Admin',
                'city' => 'Bandung',
                'occupation' => 'Admin',
                'join_date' => now(),
                'profile_photo' => null,
                'status' => 'aktif',
                'role' => 'admin',
            ],
            [
                'name' => 'Pengurus',
                'email' => 'pengurus@mail.com',
                'phone' => '081333333333',
                'gender' => 'P',
                'birth_date' => '1995-03-03',
                'address' => 'Alamat Pengurus',
                'city' => 'Surabaya',
                'occupation' => 'Pengurus',
                'join_date' => now(),
                'profile_photo' => null,
                'status' => 'aktif',
                'role' => 'pengurus',
            ],
            [
                'name' => 'Bendahara',
                'email' => 'bendahara@mail.com',
                'phone' => '081444444444',
                'gender' => 'P',
                'birth_date' => '1996-04-04',
                'address' => 'Alamat Bendahara',
                'city' => 'Medan',
                'occupation' => 'Bendahara',
                'join_date' => now(),
                'profile_photo' => null,
                'status' => 'aktif',
                'role' => 'bendahara',
            ],
            [
                'name' => 'Anggota',
                'email' => 'anggota@mail.com',
                'phone' => '081555555555',
                'gender' => 'L',
                'birth_date' => '2000-05-05',
                'address' => 'Alamat Anggota',
                'city' => 'Yogyakarta',
                'occupation' => 'Mahasiswa',
                'join_date' => now(),
                'profile_photo' => null,
                'status' => 'aktif',
                'role' => 'anggota',
            ],
        ];

        foreach ($users as $data) {
            User::create([
                ...$data,
                'password' => Hash::make($data['email']),
            ]);
        }
    }
}
