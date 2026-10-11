<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('headline')->nullable();
            $t->string('greeting')->nullable();
            $t->json('roles')->nullable();
            $t->text('tagline')->nullable();
            $t->longText('about')->nullable();
            $t->string('email')->nullable();
            $t->string('phone')->nullable();
            $t->string('location')->nullable();
            $t->boolean('available')->default(true);
            $t->string('available_text')->nullable();
            $t->json('stats')->nullable();
            $t->json('socials')->nullable();
            $t->string('accent_from')->default('#8b5cf6');
            $t->string('accent_to')->default('#06b6d4');
            $t->string('seo_title')->nullable();
            $t->text('seo_description')->nullable();
            $t->string('footer_text')->nullable();
            $t->timestamps();
        });

        Schema::create('experiences', function (Blueprint $t) {
            $t->id();
            $t->string('position');
            $t->string('company');
            $t->string('location')->nullable();
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->boolean('is_current')->default(false);
            $t->text('description')->nullable();
            $t->json('highlights')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('visible')->default(true);
            $t->timestamps();
        });

        Schema::create('education', function (Blueprint $t) {
            $t->id();
            $t->string('degree');
            $t->string('school');
            $t->string('location')->nullable();
            $t->unsignedSmallInteger('start_year')->nullable();
            $t->unsignedSmallInteger('end_year')->nullable();
            $t->text('description')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('visible')->default(true);
            $t->timestamps();
        });

        Schema::create('skill_groups', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('icon')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('visible')->default(true);
            $t->timestamps();
        });

        Schema::create('skills', function (Blueprint $t) {
            $t->id();
            $t->foreignId('skill_group_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->unsignedTinyInteger('level')->default(80);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->text('description')->nullable();
            $t->json('tags')->nullable();
            $t->string('url')->nullable();
            $t->string('repo_url')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('visible')->default(true);
            $t->timestamps();
        });

        Schema::create('languages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('level_label')->nullable();
            $t->unsignedTinyInteger('level')->default(80);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->text('body');
            $t->boolean('read')->default(false);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['messages', 'languages', 'projects', 'skills', 'skill_groups', 'education', 'experiences', 'profiles'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
