<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\CustomPermission;
use App\Models\CustomRole;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name'       => Permissions::CAN_CREATE_ACCOUNT,
            'guard_name' => 'api',
            'id'         => Uuid::uuid4()
        ]);

        Permission::create([
            'name'       => Permissions::CAN_MAKE_TRANSFER,
            'guard_name' => 'api',
            'id'         => Uuid::uuid4()
        ]);

        Permission::create([
            'name'       => Permissions::CAN_VIEW_TRANSFERS,
            'guard_name' => 'api',
            'id'         => Uuid::uuid4()
        ]);

        $customer = Role::create([
                'name'          => 'CUSTOMER',
                'guard_name' => 'api',
                'id'         => Uuid::uuid4()
        ]);

        Role::create([
                'name'          => 'SUPPORT_BENCH',
                'guard_name' => 'api',
                'id'         => Uuid::uuid4()
        ]);

        $customer  = CustomRole::findByName(Roles::CUSTOMER, 'api');

        $customer->givePermissionTo([
            CustomPermission::findByName(Permissions::CAN_MAKE_TRANSFER, 'api'),
            CustomPermission::findByName(Permissions::CAN_CREATE_ACCOUNT, 'api'),
            CustomPermission::findByName(Permissions::CAN_VIEW_TRANSFERS, 'api'),
        ]);
    }
}
