<?php

declare(strict_types=1);

namespace Tests;

use App\Tests\RefreshDatabaseWithTenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TenantTestCase extends BaseTestCase
{
    use RefreshDatabaseWithTenant;
}
