<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationResource\Pages;
use App\Models\Education;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EducationResource extends Resource
{
    use Translatable;

    protected static ?string $model = Education::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $modelLabel = 'Tanulmány';

    protected static ?string $pluralModelLabel = 'Tanulmányok';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('degree')->label('Végzettség')->required(),
            Forms\Components\TextInput::make('school')->label('Intézmény')->required(),
            Forms\Components\TextInput::make('location')->label('Helyszín'),
            Forms\Components\Toggle::make('visible')->label('Megjelenik')->default(true)->inline(false),
            Forms\Components\TextInput::make('start_year')->label('Kezdés éve')->numeric(),
            Forms\Components\TextInput::make('end_year')->label('Befejezés éve')->numeric(),
            Forms\Components\Textarea::make('description')->label('Leírás')->rows(3)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('degree')->label('Végzettség')->searchable(),
                Tables\Columns\TextColumn::make('school')->label('Intézmény'),
                Tables\Columns\TextColumn::make('end_year')->label('Végzés éve'),
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
        return ['index' => Pages\ManageEducation::route('/')];
    }
}
