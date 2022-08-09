<?php

namespace Database\Seeders;

use App\Models\Notify;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'role read','role' => 'role-employee'],
            ['name' => 'role create','role' => 'role-employee'],
            ['name' => 'role edit','role' => 'role-employee'],
            ['name' => 'role delete','role' => 'role-employee'],
            ['name' => 'employee read','role' => 'role-employee'],
            ['name' => 'employee create','role' => 'role-employee'],
            ['name' => 'employee edit','role' => 'role-employee'],
            ['name' => 'employee delete','role' => 'role-employee'],
            ['name' => 'employeeChangePassword edit','role' => 'role-employee'],
            ['name' => 'department read','role' => 'management'],
            ['name' => 'department create','role' => 'management'],
            ['name' => 'department edit','role' => 'management'],
            ['name' => 'department delete','role' => 'management'],
            ['name' => 'job read','role' => 'management'],
            ['name' => 'job create','role' => 'management'],
            ['name' => 'job edit','role' => 'management'],
            ['name' => 'job delete','role' => 'management'],
        ];


        $notifies = [

        ];

        foreach ($notifies as $notify) {
            Notify::create(['name' => $notify]);
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name'],'role' => $permission['role']]);
        }
    }
}
