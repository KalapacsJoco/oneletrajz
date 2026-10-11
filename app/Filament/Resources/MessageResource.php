<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $modelLabel = 'Üzenet';

    protected static ?string $pluralModelLabel = 'Üzenetek';

    protected static ?int $navigationSort = 7;

    public static function getNavigationBadge(): ?string
    {
        return Message::where('read', false)->count() ?: null;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Név')->disabled(),
            Forms\Components\TextInput::make('email')->label('E-mail')->disabled(),
            Forms\Components\Textarea::make('body')->label('Üzenet')->rows(8)->disabled()->columnSpanFull(),
            Forms\Components\Toggle::make('read')->label('Olvasott'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('read')->label('Olvasott')->boolean(),
                Tables\Columns\TextColumn::make('name')->label('Név')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->searchable(),
                Tables\Columns\TextColumn::make('body')->label('Üzenet')->limit(60),
                Tables\Columns\TextColumn::make('created_at')->label('Érkezett')->dateTime('Y.m.d H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()->label('Megnyitás'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageMessages::route('/')];
    }
}
