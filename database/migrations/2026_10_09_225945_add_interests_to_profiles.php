<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $t) {
            $t->json('interests')->nullable();
            $t->json('bg_motifs')->nullable();
            $t->string('interests_title')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $t) {
            $t->dropColumn(['interests', 'bg_motifs', 'interests_title']);
        });
    }
};
