<?php

namespace Database\Seeders;

use App\Models\Notify;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => '12345678',
            'role_name' => ['SuperAdmin'],
            'Status' => true,
        ]);

        $role = Role::create(['name' => 'SuperAdmin']);
        $permissions = Permission::pluck('id','id')->all();
        $notify = Notify::pluck('id');
        $role->syncPermissions($permissions);
        $role->notify()->attach($notify);
        $user->assignRole([$role->id]);

    }
}
