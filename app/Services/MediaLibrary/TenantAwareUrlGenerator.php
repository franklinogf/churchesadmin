<?php

declare(strict_types=1);

namespace App\Services\MediaLibrary;

use Override;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

final class TenantAwareUrlGenerator extends DefaultUrlGenerator
{
    #[Override]
    public function getUrl(): string
    {
        $pathRelative = $this->getPathRelativeToRoot();
        $url = tenancy()->initialized ? tenant_asset($pathRelative) : asset($pathRelative);

        return $this->versionUrl($url);
    }
}
