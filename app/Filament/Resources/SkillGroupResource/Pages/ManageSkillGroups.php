<?php

namespace App\Filament\Resources\SkillGroupResource\Pages;

use App\Filament\Resources\SkillGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageSkillGroups extends ManageRecords
{
    use Translatable;

    protected static string $resource = SkillGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
