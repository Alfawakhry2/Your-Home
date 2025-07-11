<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssignRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrcreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        $seller = Role::firstOrCreate(['name'=>'seller']);
        $seller->syncPermissions([
            'view dashboard' ,
            'view any category',
            'view any estate','view estate' , 'create estate' , 'update estate' , 'delete estate',
            'view any reservation' ,'view reservation' , 'update reservation' , 'delete reservation',
            'view any payment' ,'view payment' , 'update payment' , 'delete payment',

        ]);


        $buyer = Role::firstOrCreate(['name'=>'buyer']);
        $buyer->syncPermissions([
            'view category' ,
            'view estate' ,
            'view reservation','create reservation',
            'view payment' ,'create payment'
        ]);
    }
}
