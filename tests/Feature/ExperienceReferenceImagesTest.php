<?php

namespace Tests\Feature;

use App\Models\Discipline;
use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExperienceReferenceImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_fogtechnika_experience_shows_reference_button_when_an_image_is_uploaded(): void
    {
        Storage::fake(config('media-library.disk_name'));

        $discipline = Discipline::create([
            'name' => 'Fogtechnika',
            'slug' => 'fogtechnika',
        ]);
        $experience = Experience::create([
            'position' => 'Fogtechnikus',
            'company' => 'Minta labor',
            'discipline_id' => $discipline->id,
            'start_date' => '2025-01-01',
            'visible' => true,
        ]);

        $this->get('/hu/tapasztalat')
            ->assertOk()
            ->assertDontSee('Referenciamunkáim');

        $experience->addMedia(UploadedFile::fake()->image('reference.jpg'))
            ->toMediaCollection('reference-image');

        $this->get('/hu/tapasztalat')
            ->assertOk()
            ->assertSee('Referenciamunkáim')
            ->assertSee($experience->getFirstMediaUrl('reference-image', 'web'));
    }
}
