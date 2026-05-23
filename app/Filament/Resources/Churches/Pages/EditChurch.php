<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Pages;

use App\Filament\Resources\Churches\ChurchResource;
use App\Models\Church;
use Filament\Resources\Pages\EditRecord;

/**
 * @property-read Church $record
 */
final class EditChurch extends EditRecord
{
    protected static string $resource = ChurchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }
}
