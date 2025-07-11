<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignPermissionToUsers extends Seeder
{
    public function run()
    {
        $admin = User::where('type', 'admin')->get();
        foreach ($admin as $user) {
            $user->assignRole('admin');
        }

        $seller = User::where('type', 'seller')->get();
        foreach ($seller as $user) {
            $user->assignRole('seller');
        }


        $buyer = User::where('type', 'buyer')->get();
        foreach ($buyer as $user) {
            $user->assignRole('buyer');
        }

    }
}
