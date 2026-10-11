<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LanguageResource\Pages;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LanguageResource extends Resource
{
    use Translatable;

    protected static ?string $model = Language::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static ?string $modelLabel = 'Nyelv';

    protected static ?string $pluralModelLabel = 'Nyelvek';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nyelv')->required(),
            Forms\Components\TextInput::make('level_label')->label('Szint felirat (pl. C1, anyanyelv)'),
            Forms\Components\TextInput::make('level')->label('Szint (0-100)')->numeric()
                ->minValue(0)->maxValue(100)->default(80)->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nyelv')->searchable(),
                Tables\Columns\TextColumn::make('level_label')->label('Szint'),
                Tables\Columns\TextColumn::make('level')->label('%'),
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
        return ['index' => Pages\ManageLanguages::route('/')];
    }
}
