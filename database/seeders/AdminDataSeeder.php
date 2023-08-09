<?php

namespace Database\Seeders;

use Database\Seeders\AdminSeeder\AdminMenuDataSeeder;
use Database\Seeders\AdminSeeder\AdminPermissionDataSeeder;
use Database\Seeders\AdminSeeder\AdminRoleDataSeeder;
use Database\Seeders\AdminSeeder\AdminRoleMenuDataSeeder;
use Database\Seeders\AdminSeeder\AdminRolePermissionDataSeeder;
use Database\Seeders\AdminSeeder\AdminRoleUserDataSeeder;
use Database\Seeders\AdminSeeder\AdminUserDataSeeder;
use Illuminate\Database\Seeder;

class AdminDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Admin seed
        $this->call(AdminUserDataSeeder::class);
        $this->call(AdminMenuDataSeeder::class);
        $this->call(AdminRoleDataSeeder::class);
        $this->call(AdminPermissionDataSeeder::class);

        $this->call(AdminRoleMenuDataSeeder::class);
        $this->call(AdminRolePermissionDataSeeder::class);
        $this->call(AdminRoleUserDataSeeder::class);
    }
}
