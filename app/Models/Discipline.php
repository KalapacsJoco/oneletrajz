<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Discipline extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'tagline', 'description'];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['visible' => 'boolean'];
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function skillGroups(): HasMany
    {
        return $this->hasMany(SkillGroup::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
