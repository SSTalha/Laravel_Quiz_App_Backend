<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'User can register manager or supervisor',
            'User can change role',
            'User can approve student submission',
            'User can reject student submission',
            'User can assign quiz',
            'User can create quiz',
            'User can delete quiz',
            'User can update quiz',
            'User can view quizzes',
            'User can view assigned quizzes',
            'User can submit quiz',
            'User can view students',
            'User can attempt quizzes',
            'User can edit questions',
            'User can delete questions',
            'User can view quiz questions',
            'User can remove student',
            'User can edit student info'
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $adminRole = Role::create(['name'=> 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::create(['name'=> 'manager']);
        $managerRole->givePermissionTo([
            'User can view students',
            'User can assign quiz',
            'User can view quizzes',
            'User can view quiz questions',
            'User can view assigned quizzes',
            'User can remove student',
            'User can edit student info',
            'User can edit questions',
            'User can delete questions',
        ]);

        $supervisorRole = Role::create(['name'=> 'supervisor']);
        $supervisorRole->givePermissionTo([
            'User can view students',
            'User can assign quiz',
            'User can view quizzes',
            'User can view quiz questions',
            'User can view assigned quizzes',
            'User can edit student info',
            'User can edit questions',
        ]);

        $studentRole = Role::create(['name'=> 'student']);
        $studentRole->givePermissionTo([
            'User can view assigned quizzes',
            'User can submit quiz',
            'User can attempt quizzes',
            'User can view quiz questions',
        ]);
    }
}
