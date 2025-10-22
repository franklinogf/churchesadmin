<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Pages;

use App\Enums\ChurchFeature;
use App\Filament\Resources\Churches\ChurchResource;
use App\Models\Church;
use Filament\Actions\DeleteAction;
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

    protected function afterSave(): void
    {
        $features = $this->data['features'] ?? [];
        $existingFeatures = ChurchFeature::values();
        $featuresToDeactivate = array_diff($existingFeatures, $features);

        foreach ($featuresToDeactivate as $feature) {
            $this->record->features()->deactivate($feature);
        }

        foreach ($features as $feature) {
            $this->record->features()->activate($feature);
        }
    }
}
