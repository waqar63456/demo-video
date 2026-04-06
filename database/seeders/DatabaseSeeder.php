<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\newusers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        newusers::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'phone'=>'123456789',
            'user_type' =>'admin',
            'is_active'=>1,
            'password' => Hash::make('admin1234'), 
        ]);
    }
}
