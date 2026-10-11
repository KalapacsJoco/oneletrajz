<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $columns = [
        'profiles' => ['headline', 'greeting', 'roles', 'tagline', 'about', 'available_text', 'stats', 'interests', 'interests_title', 'footer_text', 'seo_title', 'seo_description', 'hero_word', 'quote'],
        'disciplines' => ['name', 'tagline', 'description'],
        'experiences' => ['position', 'description', 'highlights'],
        'education' => ['degree', 'description'],
        'skill_groups' => ['name'],
        'projects' => ['title', 'subtitle', 'description'],
        'languages' => ['name', 'level_label'],
    ];

    private array $jsonColumns = ['roles', 'stats', 'interests', 'highlights'];

    public function up(): void
    {
        foreach ($this->columns as $table => $columns) {
            foreach (DB::table($table)->get() as $row) {
                $update = [];

                foreach ($columns as $column) {
                    $raw = $row->{$column};

                    if ($raw === null || $raw === '' || $this->isTranslated($raw)) {
                        continue;
                    }

                    $value = in_array($column, $this->jsonColumns, true) ? json_decode($raw, true) : $raw;
                    $update[$column] = json_encode(['hu' => $value], JSON_UNESCAPED_UNICODE);
                }

                if ($update) {
                    DB::table($table)->where('id', $row->id)->update($update);
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $table => $columns) {
            foreach (DB::table($table)->get() as $row) {
                $update = [];

                foreach ($columns as $column) {
                    $raw = $row->{$column};

                    if ($raw === null || ! $this->isTranslated($raw)) {
                        continue;
                    }

                    $hu = json_decode($raw, true)['hu'] ?? null;
                    $update[$column] = is_array($hu) ? json_encode($hu, JSON_UNESCAPED_UNICODE) : $hu;
                }

                if ($update) {
                    DB::table($table)->where('id', $row->id)->update($update);
                }
            }
        }
    }

    private function isTranslated(string $raw): bool
    {
        $decoded = json_decode($raw, true);

        return is_array($decoded)
            && $decoded !== []
            && ! array_is_list($decoded)
            && array_diff(array_keys($decoded), ['hu', 'ro', 'en']) === [];
    }
};
