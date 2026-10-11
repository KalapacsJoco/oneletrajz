<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Profile;
use App\Models\SkillGroup;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResumeController extends Controller
{
    public function pdf(): Response
    {
        $profile = Profile::current();
        $filename = Str::slug($profile->name).'-'.app()->getLocale().'.pdf';

        return Pdf::loadView('pdf.resume', [
            'profile' => $profile,
            'photo' => $this->circularPhoto($profile),
            'education' => Education::where('visible', true)->orderBy('sort')->orderByDesc('start_year')->get(),
            'experiences' => Experience::where('visible', true)->orderBy('sort')->get(),
            'skillGroups' => SkillGroup::where('visible', true)->with(['skills', 'discipline'])->orderBy('sort')->get()
                ->sortBy(fn (SkillGroup $group) => array_search($group->discipline?->slug, ['informatika', 'fogtechnika', 'cad-tervezes'], true) ?? 99)
                ->values(),
            'languages' => Language::orderBy('sort')->get(),
        ])->setPaper('a4')->stream($filename);
    }

    private function circularPhoto(Profile $profile): ?string
    {
        $path = $profile->getFirstMediaPath('photo');
        $source = $path && is_file($path) ? @imagecreatefromstring((string) file_get_contents($path)) : false;

        if (! $source) {
            return null;
        }

        $size = 300;
        $side = min(imagesx($source), imagesy($source));
        $canvas = imagecreatetruecolor($size, $size);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefill($canvas, 0, 0, $transparent);
        imagecopyresampled($canvas, $source, 0, 0, intdiv(imagesx($source) - $side, 2), 0, $size, $size, $side, $side);

        $radius = $size / 2;
        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                if ((($x - $radius + .5) ** 2) + (($y - $radius + .5) ** 2) > $radius ** 2) {
                    imagesetpixel($canvas, $x, $y, $transparent);
                }
            }
        }

        ob_start();
        imagepng($canvas);

        return 'data:image/png;base64,'.base64_encode((string) ob_get_clean());
    }
}
