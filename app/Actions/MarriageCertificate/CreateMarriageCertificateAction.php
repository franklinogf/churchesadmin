<?php

declare(strict_types=1);

namespace App\Actions\MarriageCertificate;

use App\Models\MarriageCertificate;

final class CreateMarriageCertificateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): MarriageCertificate
    {
        return MarriageCertificate::query()->create($data);
    }
}
