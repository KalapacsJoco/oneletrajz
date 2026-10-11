<?php

namespace Database\Seeders;

use App\Models\Discipline;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SkillGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'kalapacs.joco79@gmail.com'], [
            'name' => 'Kalapács József',
            'password' => env('ADMIN_PASSWORD', str()->random(24)),
        ]);

        if (Profile::exists()) {
            return;
        }

        Profile::create([
            'name' => 'Kalapács József',
            'headline' => 'Full-stack fejlesztő',
            'greeting' => 'Szia, én vagyok',
            'roles' => ['Laravel fejlesztő', 'UI/UX rajongó', 'Problémamegoldó'],
            'tagline' => 'Letisztult, gyors és látványos webes élményeket építek Laravel, Livewire és Tailwind segítségével.',
            'about' => '<p>Több mint 6 éve fejlesztek webalkalmazásokat. A <strong>TALL stack</strong> (Tailwind, Alpine.js, Laravel, Livewire) a mindennapi eszköztáram, ezzel építek gyors, karbantartható és szép felületeket.</p><p>Szeretem, ha a kód és a design egyaránt elegáns – és ha a végeredményt a felhasználó is élvezi.</p>',
            'email' => 'hello@example.com',
            'phone' => '+36 30 123 4567',
            'location' => 'Budapest, Magyarország',
            'available' => true,
            'available_text' => 'Új projektekre elérhető',
            'stats' => [
                ['value' => 6, 'suffix' => '+', 'label' => 'év tapasztalat'],
                ['value' => 40, 'suffix' => '+', 'label' => 'befejezett projekt'],
                ['value' => 15, 'suffix' => '', 'label' => 'elégedett ügyfél'],
                ['value' => 100, 'suffix' => '%', 'label' => 'elköteleződés'],
            ],
            'socials' => [
                ['label' => 'GitHub', 'url' => 'https://github.com'],
                ['label' => 'LinkedIn', 'url' => 'https://linkedin.com'],
            ],
            'seo_title' => 'Kalapács József – Full-stack fejlesztő önéletrajz',
            'seo_description' => 'Minta Péter online önéletrajza: tapasztalat, képességek és projektek.',
            'footer_text' => 'Készült Laravel, Livewire és sok kávé segítségével.',
            'accent_from' => '#e63946',
            'accent_to' => '#2ec27e',
            'bg_motifs' => ['chess', 'math', 'astronomy', 'history'],
            'hero_word' => 'ÖNÉLETRAJZ',
            'quote' => 'A jó kód olyan, mint egy jó sakkjátszma: minden lépésnek oka van.',
            'quote_author' => 'Minta Péter',
            'interests_title' => 'Ami hajt',
            'interests' => [
                ['theme' => 'chess', 'title' => 'Sakk', 'text' => 'A sakk a tervezés és a türelem iskolája: több lépéssel előre gondolkodni ugyanúgy kell egy tábla mellett, mint egy architektúra megtervezésekor.'],
                ['theme' => 'math', 'title' => 'Matematika', 'text' => 'A minták és a bizonyítások szépsége. A matek tanított meg arra, hogy a bonyolult problémák is egyszerű részekre bonthatók.'],
                ['theme' => 'astronomy', 'title' => 'Csillagászat', 'text' => 'Az éjszakai égbolt emlékeztet a nagy léptékre: néha érdemes kizoomolni a napi hibakeresésből.'],
                ['theme' => 'history', 'title' => 'Történelem', 'text' => 'A múlt tanulságai a jelenben is működnek: minden rendszer és minden korszak ok-okozati láncokból épül fel.'],
            ],
        ]);

        $experiences = [
            ['Senior Laravel fejlesztő', 'Példa Kft.', 'Budapest', '2022-03-01', null, true, 'Nagy forgalmú SaaS platform fejlesztése és vezetése.', ['Teljesítmény 3x gyorsabb lekérdezésekkel', 'Csapat mentorálása', 'CI/CD bevezetése']],
            ['Webfejlesztő', 'Webstúdió Zrt.', 'Budapest', '2019-06-01', '2022-02-28', false, 'Ügyfélprojektek tervezése és megvalósítása.', ['20+ ügyfélweboldal', 'Egyedi admin felületek']],
            ['Junior fejlesztő', 'Startup Bt.', 'Debrecen', '2018-01-01', '2019-05-31', false, 'Első lépések a professzionális fejlesztésben.', []],
        ];
        foreach ($experiences as $i => [$pos, $co, $loc, $start, $end, $cur, $desc, $hl]) {
            Experience::create([
                'position' => $pos, 'company' => $co, 'location' => $loc, 'start_date' => $start,
                'end_date' => $end, 'is_current' => $cur, 'description' => $desc, 'highlights' => $hl, 'sort' => $i,
            ]);
        }

        Education::create(['degree' => 'Programtervező informatikus BSc', 'school' => 'Debreceni Egyetem', 'location' => 'Debrecen', 'start_year' => 2014, 'end_year' => 2018, 'sort' => 0]);

        $groups = [
            ['Backend', '⚙️', [['Laravel', 95], ['PHP', 92], ['MySQL', 85], ['REST API', 90]]],
            ['Frontend', '🎨', [['Livewire', 92], ['Alpine.js', 88], ['Tailwind CSS', 95], ['JavaScript', 85]]],
            ['Eszközök', '🛠️', [['Git', 90], ['Docker', 75], ['Filament', 93], ['CI/CD', 78]]],
        ];
        foreach ($groups as $i => [$name, $icon, $skills]) {
            $g = SkillGroup::create(['name' => $name, 'icon' => $icon, 'sort' => $i]);
            foreach ($skills as $j => [$sn, $lvl]) {
                $g->skills()->create(['name' => $sn, 'level' => $lvl, 'sort' => $j]);
            }
        }

        Project::create(['title' => 'Foglalási rendszer', 'subtitle' => 'SaaS platform', 'description' => 'Többbérlős időpontfoglaló alkalmazás valós idejű naptárral.', 'tags' => ['Laravel', 'Livewire', 'Filament'], 'sort' => 0]);
        Project::create(['title' => 'Webshop motor', 'subtitle' => 'E-commerce', 'description' => 'Gyors, SEO-barát webshop egyedi admin felülettel és fizetési integrációval.', 'tags' => ['Laravel', 'Stripe', 'Alpine.js'], 'sort' => 1]);
        Project::create(['title' => 'Analitikai dashboard', 'subtitle' => 'Belső eszköz', 'description' => 'Interaktív riportok és grafikonok élő adatokkal.', 'tags' => ['Livewire', 'Charts', 'Tailwind'], 'sort' => 2]);

        Language::create(['name' => 'Magyar', 'level_label' => 'Anyanyelv', 'level' => 100, 'sort' => 0]);
        Language::create(['name' => 'Angol', 'level_label' => 'C1', 'level' => 88, 'sort' => 1]);

        $this->seedDisciplines();
    }

    private function seedDisciplines(): void
    {
        $dev = Discipline::create(['name' => 'Webfejlesztés', 'slug' => 'webfejlesztes', 'icon' => '</>', 'tagline' => 'Laravel, Livewire, Filament', 'description' => 'Üzleti webalkalmazások tervezése és építése az adatbázistól a felületig.', 'sort' => 0]);
        $mentor = Discipline::create(['name' => 'Oktatás és mentorálás', 'slug' => 'oktatas', 'icon' => '♜', 'tagline' => 'Tudás átadása, csapatépítés', 'description' => 'Fejlesztők betanítása, workshopok és kódáttekintések.', 'sort' => 1]);
        $data = Discipline::create(['name' => 'Adatelemzés', 'slug' => 'adatelemzes', 'icon' => '∑', 'tagline' => 'Matematika a gyakorlatban', 'description' => 'Riportok, statisztikák és vizualizációk készítése.', 'sort' => 2]);

        Experience::where('position', 'like', 'Senior%')->update(['discipline_id' => $dev->id]);
        Experience::where('position', 'Webfejlesztő')->update(['discipline_id' => $dev->id]);
        Experience::where('position', 'like', 'Junior%')->update(['discipline_id' => $mentor->id]);
        SkillGroup::whereIn('name', ['Backend', 'Frontend'])->update(['discipline_id' => $dev->id]);
        SkillGroup::where('name', 'Eszközök')->update(['discipline_id' => $data->id]);
        Project::whereIn('title', ['Foglalási rendszer', 'Webshop motor'])->update(['discipline_id' => $dev->id, 'featured' => true]);
        Project::where('title', 'Analitikai dashboard')->update(['discipline_id' => $data->id, 'featured' => true]);
    }
}
