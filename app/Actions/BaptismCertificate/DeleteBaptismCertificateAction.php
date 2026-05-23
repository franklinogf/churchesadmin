<?php

declare(strict_types=1);

namespace App\Actions\BaptismCertificate;

use App\Models\BaptismCertificate;

final class DeleteBaptismCertificateAction
{
    public function handle(BaptismCertificate $baptismCertificate): void
    {
        $baptismCertificate->delete();
    }
}
