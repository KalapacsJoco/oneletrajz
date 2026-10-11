<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Experience extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public array $translatable = ['position', 'description', 'details', 'highlights'];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'visible' => 'boolean',
        ];
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->where('visible', true)->orderBy('sort');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reference-image')->useDisk(config('media-library.disk_name'));
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('web')->width(900)->performOnCollections('reference-image');
    }

    public function getPeriodAttribute(): string
    {
        $format = app()->getLocale() === 'hu' ? 'Y. m.' : 'm.Y';
        $end = $this->is_current || ! $this->end_date ? __('Jelenleg') : $this->end_date->format($format);

        return $this->start_date->format($format).' – '.$end;
    }
}
