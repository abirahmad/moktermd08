<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->first_name = "Super Admin";
        $admin->last_name = "";
        $admin->username = "superadmin";
        $admin->phone_no = "019XXXXXXXX";
        $admin->email = "superadmin@example.com";
        $admin->password = Hash::make('123456');
        $admin->status = 1;
        $admin->save();
        $admin->assignRole('Super Admin', 'admin');

        $admin = new User();
        $admin->first_name = "Admin";
        $admin->last_name = "";
        $admin->username = "admin";
        $admin->phone_no = "018XXXXXXXX";
        $admin->email = "admin@example.com";
        $admin->password = Hash::make('123456');
        $admin->status = 1;
        $admin->save();
        $admin->assignRole('Admin');

        $admin = new User();
        $admin->first_name = "Editor";
        $admin->last_name = "";
        $admin->username = "editor";
        $admin->phone_no = "017XXXXXXXX";
        $admin->email = "editor@example.com";
        $admin->password = Hash::make('123456');
        $admin->status = 1;
        $admin->save();
        $admin->assignRole('Editor');
    }
}
