<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Project;
use App\Models\SkillGroup;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'projects' => Project::where('visible', true)->orderByDesc('featured')->orderBy('sort')->limit(3)->get(),
            'experiences' => Experience::where('visible', true)->orderBy('sort')->limit(3)->get(),
            'skillGroups' => SkillGroup::where('visible', true)->with('skills')->orderBy('sort')->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function disciplines()
    {
        return view('pages.disciplines', [
            'items' => Discipline::where('visible', true)->withCount([
                'experiences' => fn ($q) => $q->where('visible', true),
                'projects' => fn ($q) => $q->where('visible', true),
                'skillGroups' => fn ($q) => $q->where('visible', true),
            ])->with([
                'experiences' => fn ($q) => $q->where('visible', true)->orderBy('sort'),
                'projects' => fn ($q) => $q->where('visible', true)->orderBy('sort'),
            ])->orderBy('sort')->get(),
        ]);
    }

    public function discipline(string $slug)
    {
        $discipline = Discipline::where('visible', true)->where('slug', $slug)->firstOrFail();

        return view('pages.discipline', [
            'discipline' => $discipline,
            'experiences' => $discipline->experiences()->where('visible', true)->with('media')->orderBy('sort')->get(),
            'skillGroups' => $discipline->skillGroups()->where('visible', true)->with('skills')->orderBy('sort')->get(),
            'projects' => $discipline->projects()->where('visible', true)->orderBy('sort')->get(),
        ]);
    }

    public function experience(Request $request)
    {
        $current = $this->currentDiscipline($request);

        return view('pages.experience', [
            'current' => $current,
            'experiences' => Experience::where('visible', true)
                ->when($current, fn ($q) => $q->where('discipline_id', $current->id))
                ->with('discipline', 'media', 'projects.media')->orderBy('sort')->get(),
            'education' => Education::where('visible', true)->orderBy('sort')->get(),
        ]);
    }

    public function skills(Request $request)
    {
        $current = $this->currentDiscipline($request);

        return view('pages.skills', [
            'current' => $current,
            'skillGroups' => SkillGroup::where('visible', true)
                ->when($current, fn ($q) => $q->where('discipline_id', $current->id))
                ->with('skills')->orderBy('sort')->get(),
            'languages' => Language::orderBy('sort')->get(),
        ]);
    }

    public function projects(Request $request)
    {
        $current = $this->currentDiscipline($request);

        return view('pages.projects', [
            'current' => $current,
            'projects' => Project::where('visible', true)
                ->when($current, fn ($q) => $q->where('discipline_id', $current->id))
                ->with('discipline')->orderBy('sort')->get(),
        ]);
    }

    public function interests()
    {
        return view('pages.interests');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    private function currentDiscipline(Request $request): ?Discipline
    {
        $slug = $request->query('szakma');

        return $slug ? Discipline::where('visible', true)->where('slug', $slug)->first() : null;
    }
}
