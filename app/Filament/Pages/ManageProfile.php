<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use Filament\Actions\Action;
use Filament\Actions\LocaleSwitcher;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

/**
 * @property Form $form
 */
class ManageProfile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Profil és oldalbeállítások';

    protected static ?string $title = 'Profil és oldalbeállítások';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-profile';

    public ?array $data = [];

    public Profile $record;

    public ?string $activeLocale = 'hu';

    public array $otherLocaleData = [];

    protected ?string $oldActiveLocale = null;

    public function mount(): void
    {
        $this->record = Profile::current();
        $this->activeLocale = $this->getTranslatableLocales()[0];
        $this->form->fill([
            ...Arr::except($this->record->attributesToArray(), $this->record->getTranslatableAttributes()),
            ...$this->translationsFor($this->activeLocale),
        ]);
    }

    public function getTranslatableLocales(): array
    {
        return array_keys(config('app.locales'));
    }

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }

    private function translationsFor(string $locale): array
    {
        return collect($this->record->getTranslatableAttributes())
            ->mapWithKeys(fn (string $attribute) => [$attribute => $this->record->getTranslations($attribute)[$locale] ?? null])
            ->all();
    }

    public function updatingActiveLocale(): void
    {
        $this->oldActiveLocale = $this->activeLocale;
    }

    public function updatedActiveLocale(): void
    {
        $translatable = $this->record->getTranslatableAttributes();

        $this->otherLocaleData[$this->oldActiveLocale] = Arr::only($this->data, $translatable);

        $this->data = [
            ...Arr::except($this->data, $translatable),
            ...($this->otherLocaleData[$this->activeLocale] ?? $this->translationsFor($this->activeLocale)),
        ];

        unset($this->otherLocaleData[$this->activeLocale]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->record)
            ->statePath('data')
            ->schema([
                Forms\Components\Tabs::make()->tabs([
                    Forms\Components\Tabs\Tab::make('Alapadatok')->icon('heroicon-o-user')->schema([
                        Forms\Components\TextInput::make('name')->label('Teljes név')->required(),
                        Forms\Components\TextInput::make('headline')->label('Titulus (pl. Full-stack fejlesztő)'),
                        Forms\Components\TextInput::make('greeting')->label('Üdvözlő szöveg (pl. Szia, én vagyok)'),
                        Forms\Components\TagsInput::make('roles')->label('Gépelt szerepek a főoldalon (Enter-rel adhatók hozzá)'),
                        Forms\Components\Textarea::make('tagline')->label('Rövid bemutatkozás a főoldalon')->rows(3)->columnSpanFull(),
                        Forms\Components\RichEditor::make('about')->label('Rólam szekció')->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('photo')->label('Profilkép')->collection('photo')
                            ->image()->imageEditor()
                            ->helperText('Álló, kivágott (átlátszó hátterű PNG) kép mutat a legjobban a nagy főoldali felirat előtt.'),
                        Forms\Components\TextInput::make('hero_word')->label('Nagy háttérfelirat a főoldalon (pl. ÖNÉLETRAJZ)')
                            ->helperText('Rövid, egy szavas felirat a legjobb.'),
                        Forms\Components\Textarea::make('quote')->label('Idézet / mottó (piros panel a főoldalon)')->rows(2),
                        Forms\Components\TextInput::make('quote_author')->label('Idézet szerzője'),
                        SpatieMediaLibraryFileUpload::make('cv')->label('Letölthető önéletrajz (PDF)')->collection('cv')
                            ->acceptedFileTypes(['application/pdf']),
                        Forms\Components\Toggle::make('available')->label('Elérhető új munkára')->live(),
                        Forms\Components\TextInput::make('available_text')->label('Elérhetőség felirat')
                            ->visible(fn (Forms\Get $get) => (bool) $get('available')),
                    ])->columns(2),

                    Forms\Components\Tabs\Tab::make('Elérhetőségek')->icon('heroicon-o-envelope')->schema([
                        Forms\Components\TextInput::make('email')->label('E-mail')->email(),
                        Forms\Components\TextInput::make('phone')->label('Telefon'),
                        Forms\Components\TextInput::make('location')->label('Lakhely')->columnSpanFull(),
                        Forms\Components\TextInput::make('website')->label('Weboldal')->url(),
                        Forms\Components\DatePicker::make('birth_date')->label('Születési dátum'),
                        Forms\Components\TextInput::make('birth_place')->label('Születési hely'),
                        Forms\Components\TextInput::make('nationality')->label('Állampolgárság'),
                        Forms\Components\TextInput::make('marital_status')->label('Családi állapot'),
                        Forms\Components\TextInput::make('driving_license')->label('Jogosítvány (pl. B kategória)'),
                        Forms\Components\Repeater::make('socials')->label('Közösségi linkek')
                            ->schema([
                                Forms\Components\TextInput::make('label')->label('Név (pl. GitHub)')->required(),
                                Forms\Components\TextInput::make('url')->label('URL')->url()->required(),
                            ])->columns(2)->collapsible()->columnSpanFull()
                            ->itemLabel(fn (array $state) => $state['label'] ?? null),
                    ])->columns(2),

                    Forms\Components\Tabs\Tab::make('Számok')->icon('heroicon-o-chart-bar')->schema([
                        Forms\Components\Repeater::make('stats')->label('Kiemelt számok (animált számlálók)')
                            ->schema([
                                Forms\Components\TextInput::make('value')->label('Érték')->numeric()->required(),
                                Forms\Components\TextInput::make('suffix')->label('Utótag (pl. +, %)'),
                                Forms\Components\TextInput::make('label')->label('Felirat')->required(),
                            ])->columns(3)->collapsible()->columnSpanFull()
                            ->itemLabel(fn (array $state) => $state['label'] ?? null),
                    ]),

                    Forms\Components\Tabs\Tab::make('Szenvedélyek')->icon('heroicon-o-sparkles')->schema([
                        Forms\Components\TextInput::make('interests_title')->label('Szekció címe (pl. Ami hajt)')->columnSpanFull(),
                        Forms\Components\Repeater::make('interests')->label('Érdeklődési körök')
                            ->schema([
                                Forms\Components\Select::make('theme')->label('Téma (ez adja a kártya animációját)')
                                    ->options([
                                        'chess' => '♞ Sakk', 'math' => 'π Matematika',
                                        'astronomy' => '☉ Csillagászat', 'history' => '⚜ Történelem', 'music' => '𝄞 Zene', 'other' => '✦ Egyéb',
                                    ])->required(),
                                Forms\Components\TextInput::make('title')->label('Cím')->required(),
                                Forms\Components\Textarea::make('text')->label('Leírás')->rows(3)->columnSpanFull(),
                            ])->columns(2)->collapsible()->columnSpanFull()
                            ->itemLabel(fn (array $state) => $state['title'] ?? null),
                    ]),

                    Forms\Components\Tabs\Tab::make('Megjelenés és SEO')->icon('heroicon-o-paint-brush')->schema([
                        Forms\Components\CheckboxList::make('bg_motifs')->label('Háttér motívumok')
                            ->options([
                                'chess' => 'Sakk (bábuk, lépések)', 'math' => 'Matematika (képletek, szimbólumok)',
                                'astronomy' => 'Csillagászat (csillagok, csillagképek, bolygók)', 'history' => 'Történelem (római számok, jelképek)',
                                'music' => 'Zene (violinkulcs, hangjegyek, kottajelek)',
                            ])->columns(2)->columnSpanFull(),
                        Forms\Components\ColorPicker::make('accent_from')->label('Főszín (piros)'),
                        Forms\Components\ColorPicker::make('accent_to')->label('Másodlagos szín (zöld)'),
                        Forms\Components\TextInput::make('footer_text')->label('Lábléc szöveg')->columnSpanFull(),
                        Forms\Components\TextInput::make('seo_title')->label('Oldal címe (title)')->columnSpanFull(),
                        Forms\Components\Textarea::make('seo_description')->label('Meta leírás')->rows(2)->columnSpanFull(),
                    ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Mentés')->submit('save'),
        ];
    }

    public function save(): void
    {
        $translatable = $this->record->getTranslatableAttributes();
        $data = $this->form->getState();

        $this->otherLocaleData[$this->activeLocale] = Arr::only($data, $translatable);
        $this->record->fill(Arr::except($data, $translatable));

        foreach ($this->otherLocaleData as $locale => $values) {
            foreach ($values as $attribute => $value) {
                if (blank($value) && $locale !== $this->getTranslatableLocales()[0]) {
                    $this->record->forgetTranslation($attribute, $locale);
                } else {
                    $this->record->setTranslation($attribute, $locale, $value);
                }
            }
        }

        $this->record->save();
        $this->form->model($this->record)->saveRelationships();

        Notification::make()->title('Mentve')->success()->send();
    }
}
