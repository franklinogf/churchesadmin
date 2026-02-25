<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use Illuminate\Database\Eloquent\Model;
use Override;

final class TestModel extends Model
{
    protected $fillable = ['id', 'name', 'title', 'description'];

    #[Override]
    public function getMorphClass(): string
    {
        return 'TestModel';
    }
}
