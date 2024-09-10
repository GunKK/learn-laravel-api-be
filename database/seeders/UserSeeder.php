<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
        DB::table('users')->insert([
            [
                'email' => 'admin@hcmut.edu.vn',
                'name' => 'admin',
                'password' => Hash::make('admin12345678'),
                'email_verified_at' => Carbon::now(),
                'role_id' => 1,
                'avatar' => 'https://gravatar.com/avatar/d36a92237c75c5337c17b60d90686bf9?size=200',
            ],
            [
                'email' => 'supervisor@hcmut.edu.vn',
                'name' => 'Hau supervisor',
                'password' => Hash::make('supervisor12345678'),
                'email_verified_at' => Carbon::now(),
                'role_id' => 2,
                'avatar' => 'https://gravatar.com/avatar/d36a92237c75c5337c17b60d90686bf9?size=200',
            ],
            [
                'email' => 'teacher@hcmut.edu.vn',
                'name' => 'Hau teacher',
                'password' => Hash::make('teacher12345678'),
                'email_verified_at' => Carbon::now(),
                'role_id' => 3,
                'avatar' => 'https://gravatar.com/avatar/d36a92237c75c5337c17b60d90686bf9?size=200',
            ],
            [
                'email' => 'hau.nguyenbk19@hcmut.edu.vn',
                'name' => 'Hau customer',
                'password' => Hash::make('hau.nguyenbk19'),
                'email_verified_at' => Carbon::now(),
                'role_id' => 4,
                'avatar' => 'https://gravatar.com/avatar/d36a92237c75c5337c17b60d90686bf9?size=200',
            ],
        ]);
    }
}
