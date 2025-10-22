<?php

declare(strict_types=1);

namespace App\Filament\Resources\Churches\Pages;

use App\Enums\ChurchFeature;
use App\Filament\Resources\Churches\ChurchResource;
use App\Models\Church;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Laravel\Pennant\Feature;

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
            Feature::for($this->record)->deactivate($feature);
        }

        foreach ($features as $feature) {
            Feature::for($this->record)->activate($feature);
        }
    }
}
