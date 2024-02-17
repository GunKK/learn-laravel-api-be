<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users') ->insert([
            [
                'email'  =>'admin@hcmut.edu.vn',
                'name' => 'admin',
                'password' =>  Hash::make('admin12345678'),
                'email_verified_at' => '2024-01-24 08:54:15',
                'role_id' => 1,
            ],
            [
                'email'  =>'supervisor@hcmut.edu.vn',
                'name' => 'Hau supervisor',
                'password' =>  Hash::make('supervisor12345678'),
                'email_verified_at' => '2024-01-24 08:54:15',
                'role_id' => 2,
            ],
            [
                'email'  =>'employee@hcmut.edu.vn',
                'name' => 'Hau employee',
                'password' =>  Hash::make('employee12345678'),
                'email_verified_at' => '2024-01-24 08:54:15',
                'role_id' => 3,
            ],
            [
                'email'  =>'hau.nguyebk19@hcmut.edu.vn',
                'name' => 'Hau customer',
                'password' =>  Hash::make('hau.nguyebk19'),
                'email_verified_at' => '2024-01-24 08:54:15',
                'role_id' => 4,
            ],
        ]);
    }
}
