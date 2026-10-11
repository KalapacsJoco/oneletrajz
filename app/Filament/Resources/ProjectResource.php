<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    use Translatable;

    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $modelLabel = 'Projekt';

    protected static ?string $pluralModelLabel = 'Projektek';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('Cím')->required(),
            Forms\Components\TextInput::make('subtitle')->label('Alcím / szerep'),
            Forms\Components\Select::make('discipline_id')->label('Szakma')->relationship('discipline', 'name')->placeholder('Nincs / általános'),
            Forms\Components\Select::make('experience_id')->label('Munkahely (ahol a referenciamunka megjelenik)')
                ->relationship('experience', 'company')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->company.' – '.$record->position)
                ->placeholder('Nincs hozzárendelve'),
            Forms\Components\Toggle::make('featured')->label('Kiemelt (főoldalon is megjelenik)')->inline(false),
            Forms\Components\TextInput::make('url')->label('Élő link')->url(),
            Forms\Components\TextInput::make('repo_url')->label('Repository link')->url(),
            Forms\Components\Textarea::make('description')->label('Leírás')->rows(3)->columnSpanFull(),
            Forms\Components\TagsInput::make('tags')->label('Technológiák')->columnSpanFull(),
            SpatieMediaLibraryFileUpload::make('image')->label('Képek (több is feltölthető, húzással átrendezhető)')->collection('image')
                ->image()->imageEditor()->multiple()->reorderable()->panelLayout('grid')->columnSpanFull(),
            Forms\Components\Toggle::make('visible')->label('Megjelenik')->default(true)->inline(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->label('Kép')->collection('image'),
                Tables\Columns\TextColumn::make('title')->label('Cím')->searchable(),
                Tables\Columns\TextColumn::make('tags')->label('Technológiák')->badge(),
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
        return ['index' => Pages\ManageProjects::route('/')];
    }
}
