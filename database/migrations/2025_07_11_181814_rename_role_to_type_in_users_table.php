<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['admin', 'seller', 'buyer'])
                ->nullable()
                ->after('role');
        });

        DB::table('users')->update([
            'type' => DB::raw('role')
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['admin', 'seller', 'buyer'])
                ->default('buyer')
                ->change();
        });
    }


    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('type', 'role');
        });
    }
};
