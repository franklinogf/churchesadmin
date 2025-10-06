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
        } catch (Exception $th) {
            throw new Exception('Failed to delete missionary: '.$th->getMessage(), $th->getCode(), $th);
        }
        activity(ModelMorphName::MISSIONARY->activityLogName())
            ->event('force deleted')
            ->performedOn($missionary)
            ->log('Missionary :subject.name force deleted');
    }
}
