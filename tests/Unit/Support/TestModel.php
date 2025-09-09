<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use Illuminate\Database\Eloquent\Model;

final class TestModel extends Model
{
    protected $fillable = ['id', 'name', 'title', 'description'];

    public function getMorphClass()
    {
        return 'TestModel';
    }
}
