<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where("name","admin")->first();

        if(!$adminRole) {
            $this->call(RolesAndPermissionsSeeder::class);
            $adminRole = Role::where("name","admin")->first();
        }
        
        $adminuser = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password@123'),
            ]
        );  
        $adminuser->assignRole('admin');
    }
}
