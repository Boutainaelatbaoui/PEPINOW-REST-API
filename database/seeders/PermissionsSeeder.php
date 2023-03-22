<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'seller']);
        Role::create(['name' => 'user']);

        Permission::create(['name' => 'manage plants']);
        Permission::create(['name' => 'consult plants']);
        Permission::create(['name' => 'filter by categories']);
        Permission::create(['name' => 'view plant']);
        Permission::create(['name' => 'update plants']);
        Permission::create(['name' => 'delete plants']);
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage permissions']);

        Role::findByName('admin')->givePermissionTo(['manage roles', 'manage permissions', 'update plants', 'delete plants', 'manage categories']);
        Role::findByName('seller')->givePermissionTo(['manage plants']);
        Role::findByName('user')->givePermissionTo(['consult plants', 'filter by categories', 'view plant']);

        $user = \App\Models\User::factory()->create([
            'name' => 'Boutaina',
            'email' => 'boutaina123@gmail.com',
        ]);
        $user->assignRole('admin');

        $password = 'my_password'; // Replace with your desired password

        $user->password = Hash::make($password);
        $user->save();
    }
}
