<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisciplineResource\Pages;
use App\Filament\Resources\DisciplineResource\RelationManagers;
use App\Models\Discipline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisciplineResource extends Resource
{
    use Translatable;

    protected static ?string $model = Discipline::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $modelLabel = 'Szakma';

    protected static ?string $pluralModelLabel = 'Szakmák';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Szakma neve')->required()->live(onBlur: true)
                ->afterStateUpdated(fn (Forms\Set $set, ?string $state, ?string $old, Forms\Get $get) => $get('slug') ? null : $set('slug', \Illuminate\Support\Str::slug((string) $state))),
            Forms\Components\TextInput::make('slug')->label('URL azonosító')->required()->unique(ignoreRecord: true)->alphaDash(),
            Forms\Components\TextInput::make('icon')->label('Emoji vagy jel')->maxLength(4),
            Forms\Components\Toggle::make('visible')->label('Megjelenik')->default(true)->inline(false),
            Forms\Components\TextInput::make('tagline')->label('Rövid mondat')->columnSpanFull(),
            Forms\Components\Textarea::make('description')->label('Bemutatkozás ehhez a szakmához')->rows(4)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')->label(''),
                Tables\Columns\TextColumn::make('name')->label('Szakma')->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('URL'),
                Tables\Columns\IconColumn::make('visible')->label('Látható')->boolean(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDisciplines::route('/'),
        ];
    }
}
