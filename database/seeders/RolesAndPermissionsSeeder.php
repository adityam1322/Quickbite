<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'restaurants.view',
            'restaurants.create',
            'restaurants.update',
            'restaurants.delete',

            'menus.view',
            'menus.create',
            'menus.update',
            'menus.delete',

            'orders.view',
            'orders.create',
            'orders.update',
            'orders.manage',

            'deliveries.view',
            'deliveries.update',
            'deliveries.manage',

            'addresses.view',
            'addresses.create',
            'addresses.update',
            'addresses.delete',

            'payments.view',
            'payments.create',

            'refunds.create',

            'ratings.create',

            'reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $customer = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);

        $restaurantStaff = Role::firstOrCreate([
            'name' => 'restaurant_staff',
            'guard_name' => 'web',
        ]);

        $deliveryPartner = Role::firstOrCreate([
            'name' => 'delivery_partner',
            'guard_name' => 'web',
        ]);

        $administrator = Role::firstOrCreate([
            'name' => 'administrator',
            'guard_name' => 'web',
        ]);

        $customer->syncPermissions([
            'restaurants.view',
            'menus.view',
            'orders.view',
            'orders.create',
            'addresses.view',
            'addresses.create',
            'addresses.update',
            'addresses.delete',
            'payments.view',
            'payments.create',
            'ratings.create',
        ]);

        $restaurantStaff->syncPermissions([
            'restaurants.view',
            'restaurants.update',

            'menus.view',
            'menus.create',
            'menus.update',
            'menus.delete',

            'orders.view',
            'orders.manage',
        ]);

        $deliveryPartner->syncPermissions([
            'orders.view',
            'deliveries.view',
            'deliveries.update',
        ]);

        $administrator->syncPermissions(
            Permission::all()
        );
    }
}