<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            'user' , 'category' , 'estate' , 'reservation' , 'payment'
        ];

        $actions = [
            'view any','view' ,'create' , 'update' , 'delete'
        ];

        foreach($models as $model){
            foreach($actions as $action){
                Permission::firstOrcreate(['name' => "$action $model"]);
            }
        }


        Permission::firstOrcreate(['name'=>'view dashboard']);
    }
}
