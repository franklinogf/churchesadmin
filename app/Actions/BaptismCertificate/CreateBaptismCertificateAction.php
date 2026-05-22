<?php

declare(strict_types=1);

namespace App\Actions\BaptismCertificate;

use App\Models\BaptismCertificate;

final class CreateBaptismCertificateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): BaptismCertificate
    {
        return BaptismCertificate::query()->create($data);
    }
}
