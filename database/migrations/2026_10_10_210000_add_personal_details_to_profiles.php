<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->text('nationality')->nullable();
            $table->text('marital_status')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('website')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'birth_place', 'nationality', 'marital_status', 'driving_license', 'website']);
        });
    }
};
