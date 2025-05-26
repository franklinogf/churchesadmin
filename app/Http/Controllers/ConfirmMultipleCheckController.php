<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Check\ConfirmCheckAction;
use App\Enums\FlashMessageKey;
use App\Exceptions\WalletException;
use App\Http\Requests\Check\ConfirmMultipleCheckRequest;
use App\Models\Check;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

final class ConfirmMultipleCheckController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ConfirmMultipleCheckRequest $request, ConfirmCheckAction $action): RedirectResponse
    {
        /**
         * @var string[] $checkIds
         */
        $checkIds = $request->array('checks');

        try {
            DB::transaction(function () use ($checkIds, $action): void {
                Check::whereIn('id', $checkIds)->each(function (Check $check) use ($action): void {
                    $action->handle($check);
                });
            });
        } catch (WalletException $e) {
            return back()->with(FlashMessageKey::ERROR->value, $e->getMessage());
        }

        return back()->with(FlashMessageKey::SUCCESS->value, __('flash.message.check.confirmed'));
    }
}
