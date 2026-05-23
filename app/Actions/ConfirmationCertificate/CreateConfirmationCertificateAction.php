<?php

declare(strict_types=1);

namespace App\Actions\ConfirmationCertificate;

use App\Models\ConfirmationCertificate;

final class CreateConfirmationCertificateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): ConfirmationCertificate
    {
        return ConfirmationCertificate::query()->create($data);
    }
}
