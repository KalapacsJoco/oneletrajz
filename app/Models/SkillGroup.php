<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class SkillGroup extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['visible' => 'boolean'];
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('sort');
    }
}
