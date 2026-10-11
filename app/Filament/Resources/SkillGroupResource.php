<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkillGroupResource\Pages;
use App\Models\SkillGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SkillGroupResource extends Resource
{
    use Translatable;

    protected static ?string $model = SkillGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $modelLabel = 'Képességcsoport';

    protected static ?string $pluralModelLabel = 'Képességek';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Csoport neve')->required(),
            Forms\Components\TextInput::make('icon')->label('Emoji vagy rövid jel (pl. ⚡)')
                ->maxLength(4)
                ->helperText('A kártya sarkában jelenik meg.'),
            Forms\Components\Select::make('discipline_id')->label('Szakma')->relationship('discipline', 'name')->placeholder('Nincs / általános'),
            Forms\Components\Toggle::make('visible')->label('Megjelenik')->default(true),
            Forms\Components\Repeater::make('skills')->relationship('skills')->label('Képességek')
                ->schema([
                    Forms\Components\TextInput::make('name')->label('Név')->required(),
                    Forms\Components\TextInput::make('level')->label('Szint (0-100)')->numeric()
                        ->minValue(0)->maxValue(100)->default(80)->required(),
                ])
                ->columns(2)->orderColumn('sort')->reorderable()->collapsible()
                ->itemLabel(fn (array $state) => $state['name'] ?? null)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Csoport')->searchable(),
                Tables\Columns\TextColumn::make('skills_count')->counts('skills')->label('Képességek száma'),
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
        return ['index' => Pages\ManageSkillGroups::route('/')];
    }
}
