<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Education extends Model
{
    use HasTranslations;

    public array $translatable = ['degree', 'description'];

    protected $table = 'education';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['visible' => 'boolean'];
    }
}
