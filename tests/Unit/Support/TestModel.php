<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Override;

#[Fillable(['id', 'name', 'title', 'description'])]
final class TestModel extends Model
{
    #[Override]
    public function getMorphClass(): string
    {
        return 'TestModel';
    }
}
