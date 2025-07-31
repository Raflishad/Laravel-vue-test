<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            
            $table->uuid('auditable_id')->change();
        });
    }

    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->unsignedBigInteger('auditable_id')->change();
        });
    }
};
