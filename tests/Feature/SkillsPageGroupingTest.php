<?php

namespace Tests\Feature;

use App\Models\Discipline;
use App\Models\SkillGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillsPageGroupingTest extends TestCase
{
    use RefreshDatabase;

    public function test_skill_groups_are_separated_into_blocks_by_discipline(): void
    {
        $it = Discipline::create(['name' => 'Informatika', 'slug' => 'informatika', 'sort' => 2]);
        $dental = Discipline::create(['name' => 'Fogtechnika', 'slug' => 'fogtechnika', 'sort' => 1]);
        Discipline::create(['name' => 'Üzemvezetés', 'slug' => 'uzemvezetes', 'sort' => 3]);

        SkillGroup::create(['name' => 'Backend', 'discipline_id' => $it->id]);
        SkillGroup::create(['name' => 'ExoCad csoport', 'discipline_id' => $dental->id]);

        $this->get('/hu/keszsegek')
            ->assertOk()
            ->assertSeeInOrder(['Fogtechnika', 'ExoCad csoport', 'Informatika', 'Backend'])
            ->assertDontSee('Üzemvezetés</h2>', false);
    }
}
