<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Enums\ModelMorphName;
use App\Models\Missionary;
use Exception;
use Illuminate\Support\Facades\DB;

final class ForceDeleteMissionaryAction
{
    /**
     * Handle the action.
     */
    public function handle(Missionary $missionary): void
    {
        try {
            DB::transaction(function () use ($missionary): void {
                if ($missionary->address) {
                    $missionary->address->delete();
                }

                $missionary->forceDelete();
            });
        } catch (Exception $exception) {
            throw new Exception('Failed to delete missionary: '.$exception->getMessage(), $exception->getCode(), $exception);
        }

        activity(ModelMorphName::MISSIONARY->activityLogName())
            ->event('force deleted')
            ->performedOn($missionary)
            ->log('Missionary :subject.name force deleted');
    }
}
