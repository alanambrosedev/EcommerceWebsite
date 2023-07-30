<?php

namespace Database\Seeders;

use App\Models\Admin;
use Hash;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123456');
        $adminRecords = [
            ['id' => 2, 'name' => 'Alan Ambrose', 'type' => 'subadmin', 'mobile' => 7012756684, 'email' => 'alan@admin.com', 'password' => $password, 'image' => '', 'status' => 1],
            ['id' => 3, 'name' => 'Safal PA', 'type' => 'subadmin', 'mobile' => 7012756684, 'email' => 'safal@admin.com', 'password' => $password, 'image' => '', 'status' => 1],
        ];
        Admin::insert($adminRecords);
    }
}
