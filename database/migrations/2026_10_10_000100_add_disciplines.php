<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplines', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('icon')->nullable();
            $t->string('tagline')->nullable();
            $t->text('description')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('visible')->default(true);
            $t->timestamps();
        });

        foreach (['experiences', 'skill_groups', 'projects'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('discipline_id')->nullable()->constrained()->nullOnDelete();
            });
        }

        Schema::table('projects', function (Blueprint $t) {
            $t->boolean('featured')->default(false);
        });

        Schema::table('profiles', function (Blueprint $t) {
            $t->string('hero_word')->nullable();
            $t->text('quote')->nullable();
            $t->string('quote_author')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $t) {
            $t->dropColumn('featured');
        });

        Schema::table('profiles', function (Blueprint $t) {
            $t->dropColumn(['hero_word', 'quote', 'quote_author']);
        });

        foreach (['experiences', 'skill_groups', 'projects'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropConstrainedForeignId('discipline_id');
            });
        }

        Schema::dropIfExists('disciplines');
    }
};
