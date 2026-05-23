<?php

declare(strict_types=1);

namespace App\Actions\FirstCommunionCertificate;

use App\Models\FirstCommunionCertificate;

final class DeleteFirstCommunionCertificateAction
{
    public function handle(FirstCommunionCertificate $firstCommunionCertificate): void
    {
        $firstCommunionCertificate->delete();
    }
}
