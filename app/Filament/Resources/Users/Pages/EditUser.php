<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Prevent users from editing themselves
        if ($this->getRecord()->id === Auth::id()) {
            abort(Response::HTTP_FORBIDDEN, 'You cannot edit your own profile.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
