<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('skills')->get() as $skill) {
            $decoded = json_decode($skill->name, true);

            if (is_array($decoded) && array_key_exists('hu', $decoded)) {
                continue;
            }

            DB::table('skills')->where('id', $skill->id)->update([
                'name' => json_encode(['hu' => $skill->name], JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    public function down(): void
    {
        foreach (DB::table('skills')->get() as $skill) {
            $decoded = json_decode($skill->name, true);

            if (is_array($decoded) && array_key_exists('hu', $decoded)) {
                DB::table('skills')->where('id', $skill->id)->update(['name' => $decoded['hu']]);
            }
        }
    }
};
