<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Discipline;
use App\Models\Experience;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExperienceResource extends Resource
{
    use Translatable;

    protected static ?string $model = Experience::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $modelLabel = 'Munkatapasztalat';

    protected static ?string $pluralModelLabel = 'Munkatapasztalatok';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('position')->label('Pozíció')->required(),
            Forms\Components\TextInput::make('company')->label('Cég')->required(),
            Forms\Components\Select::make('discipline_id')->label('Szakma')->relationship('discipline', 'name')->placeholder('Nincs / általános')->live(),
            Forms\Components\TextInput::make('location')->label('Helyszín'),
            Forms\Components\Toggle::make('visible')->label('Megjelenik')->default(true)->inline(false),
            Forms\Components\DatePicker::make('start_date')->label('Kezdés')->required(),
            Forms\Components\DatePicker::make('end_date')->label('Befejezés')
                ->hidden(fn (Forms\Get $get) => (bool) $get('is_current')),
            Forms\Components\Toggle::make('is_current')->label('Jelenlegi munkahely')->live(),
            Forms\Components\Textarea::make('description')->label('Rövid leírás (mindig látszik)')->rows(3)->columnSpanFull(),
            Forms\Components\RichEditor::make('details')->label('Részletes munkaköri leírás (a „Bővebben” gombra nyílik le)')
                ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'h3', 'link', 'undo', 'redo'])
                ->columnSpanFull(),
            Forms\Components\TagsInput::make('highlights')->label('Kiemelések (Enter-rel adhatók hozzá)')->columnSpanFull(),
            SpatieMediaLibraryFileUpload::make('reference-image')
                ->label('Referencia képek')
                ->collection('reference-image')
                ->image()
                ->multiple()
                ->reorderable()
                ->panelLayout('grid')
                ->columnSpanFull()
                ->visible(fn (Forms\Get $get): bool => Discipline::query()
                    ->whereKey($get('discipline_id'))
                    ->where('slug', 'fogtechnika')
                    ->exists()),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('position')->label('Pozíció')->searchable(),
                Tables\Columns\TextColumn::make('company')->label('Cég')->searchable(),
                Tables\Columns\TextColumn::make('period')->label('Időszak'),
                Tables\Columns\IconColumn::make('visible')->label('Látható')->boolean(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageExperiences::route('/')];
    }
}
