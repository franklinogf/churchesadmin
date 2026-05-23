<?php

declare(strict_types=1);

namespace App\Actions\FirstCommunionCertificate;

use App\Models\FirstCommunionCertificate;

final class CreateFirstCommunionCertificateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): FirstCommunionCertificate
    {
        return FirstCommunionCertificate::query()->create($data);
    }
}
