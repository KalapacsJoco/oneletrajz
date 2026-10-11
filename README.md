# Online önéletrajz (TALL stack + Filament)

Laravel 13 · Livewire 3 · Alpine.js · Tailwind CSS 4 · Filament 3 · GSAP + Lenis animációk.

## Indítás

```
composer install && npm install
php artisan migrate --seed
php artisan storage:link
npm run build   # fejlesztéskor: npm run dev
```

Az oldal: `/` · Admin: `/admin` (`admin@example.com` / `password` – **élesben változtasd meg**).

## Tartalom szerkesztése (Filament)

Profil és oldalbeállítások (név, bemutatkozás, fotó, CV PDF, színek, SEO, számlálók, közösségi linkek),
munkatapasztalat, tanulmányok, képességek, projektek (képpel), nyelvek, beérkezett üzenetek.
A listák húzással átrendezhetők.

## Oldalak és szakmák

Minden rész külön oldal: `/`, `/rolam`, `/szakmak`, `/szakmak/{azonosító}`, `/tapasztalat`, `/keszsegek`,
`/projektek`, `/szenvedelyek`, `/kapcsolat`. Az admin **Szakmák** menüjében felvehetsz tetszőleges számú szakmát;
a tapasztalatot, képességcsoportot és projektet szakmához rendelve megjelenik a szakma saját oldalán, a listaoldalakon
pedig szakma szerint szűrhető (`?szakma=azonosító`). A főoldali nagy háttérfeliratot, a mottót és a háttérmotívumokat
a Profil oldalon állíthatod. A legjobb hatáshoz átlátszó hátterű, álló profilképet tölts fel.
