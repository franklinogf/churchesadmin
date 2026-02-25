<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Check\UpdateCheckAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\Check\GenerateCheckNumberRequest;
use App\Models\Check;
use Illuminate\Http\RedirectResponse;

use function count;

final class GenerateCheckNumberController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GenerateCheckNumberRequest $request, UpdateCheckAction $action): RedirectResponse
    {

        $checkNumber = $request->integer('initial_check_number');
        /**
         * @var string[] $checkIds
         */
        $checkIds = $request->array('checks');

        $existingCheckNumbers = [];

        Check::unconfirmed()->update(['check_number' => null]);

        foreach ($checkIds as $checkId) {
            $check = Check::find($checkId);

            if (! $check instanceof Check) {
                continue;
            }

            while (Check::confirmed()->where('check_number', $checkNumber)->exists()) {
                $existingCheckNumbers[] = $checkNumber;
                $checkNumber++;
            }

            $action->handle($check, [
                'check_number' => (string) $checkNumber,
            ]);

            $checkNumber++;
        }

        $message = $existingCheckNumbers === []
        ? null
              : trans_choice(
                  'flash.message.check.number_exists',
                  count($existingCheckNumbers),
                  ['numbers' => implode(', ', $existingCheckNumbers)]
              );

        return back()
            ->with(FlashMessageKey::SUCCESS->value, __('flash.message.check.number_generated'))
            ->with(FlashMessageKey::MESSAGE->value, $message);
    }
}
