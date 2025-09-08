<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Churches\ChurchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListChurches extends ListRecords
{
    protected static string $resource = ChurchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
