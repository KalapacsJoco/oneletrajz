<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Profile extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public array $translatable = [
        'headline', 'greeting', 'roles', 'tagline', 'about', 'available_text', 'stats', 'interests',
        'interests_title', 'footer_text', 'seo_title', 'seo_description', 'hero_word', 'quote',
        'nationality', 'marital_status',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'socials' => 'array',
            'bg_motifs' => 'array',
            'available' => 'boolean',
            'birth_date' => 'date',
        ];
    }

    public static function current(): self
    {
        return static::query()->first() ?? static::create(['name' => 'Az Ön Neve', 'accent_from' => '#e63946', 'accent_to' => '#2ec27e']);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
        $this->addMediaCollection('cv')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('web')->width(900)->performOnCollections('photo');
    }
}
