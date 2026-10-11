<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'level_label'];

    protected $guarded = [];
}
